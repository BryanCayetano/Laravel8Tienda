<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class categoriaController extends Controller
{
    public function insert(Request $request)
    {
        if (Auth::user()->rol == 0) {
            // Si no es administrador, devolvemos una respuesta JSON indicando que no tiene permiso.
            return response()->json([
                "status" => 0,
                "msg" => "No eres admin",
            ]);
        } else {
            // Si el usuario es un administrador, validamos los datos de la categoría.
            $request->validate([
                'nombre' => 'required | unique:categoria',
                'descripcion' => 'required',
                'precio' => 'required',
            ]);

            // Creamos un objeto Categoria y le asignamos los datos de la nueva categoría.
            $Categoria = new Categoria();
            $Categoria->nombre = $request->nombre;
            $Categoria->descripcion = $request->descripcion;
            $Categoria->save();

            return response()->json([
                "status" => 1,
                "msg" => "¡Registro de categoría exitoso!",
            ]);
        }
    }

    public function update(Request $request, $id_categoria)
    {
        if (Auth::user()->rol == 0) {
            return response()->json([
                "status" => 0,
                "msg" => "No eres admin",
            ]);
        } else {
            $categoria = Categoria::find($id_categoria);
            if (isset($categoria)) {
                $old_name = $categoria->nombre;
                // guardamos el nombre anterior por si hay que actualizar las referencias

                $request->validate([
                    'nombre' => 'required|unique:categoria,nombre,' . $id_categoria . ',id_categoria',
                    'descripcion' => 'required',
                ]);

                $categoria->nombre = $request->nombre;
                $categoria->descripcion = $request->descripcion;

                // Actualizamos las referencias a la categoría si se cambia el nombre
                if ($old_name !== $request->nombre) {
                    DB::table('productos')->where('id_categoria', $id_categoria)->update(['nombre_categoria' => $request->nombre]);
                    DB::table('carrito')->where('id_categoria', $id_categoria)->update(['nombre_categoria' => $request->nombre]);
                }

                $categoria->save();

                return response()->json([
                    "status" => 1,
                    "msg" => "¡Categoría modificada con éxito!",
                ]);
            }

            return response()->json([
                "status" => 0,
                "msg" => "La categoría no existe",
            ]);
        }
    }

    public function delete(Request $request)
    {
        // Verificar si el usuario es un administrador
        if (Auth::user()->rol == 0) {
            return response()->json([
                "status" => 0,
                "msg" => "No eres admin",
            ]);
        } else {
            // Obtener el ID de la categoría a eliminar del Request
            $id_categoria = $request->id_categoria;

            // Buscar la categoría en la base de datos
            $categoria = Categoria::find($id_categoria);

            if ($categoria) {
                // Si la categoría existe, eliminarla de la base de datos
                $categoria->delete();

                return response()->json([
                    "status" => 1,
                    "msg" => "¡Categoría eliminada!",
                ]);
            } else {
                // Si la categoría no existe, devolver un mensaje de error
                return response()->json([
                    "status" => 0,
                    "msg" => "La categoría no existe",
                ]);
            }
        }
    }
}
