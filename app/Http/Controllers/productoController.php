<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class productoController extends Controller
{

    public function index()
    {
        // Recuperar todos los productos
        $productos = Producto::all();

        // Retornar vista con los productos
        return view('productos.index', compact('productos'));
    }
    
    public function insert(Request $request)
    {
        // Verificar si el usuario es un administrador
        if (Auth::user()->rol == 0) {
            return response()->json([
                "status" => 0,
                "msg" => "No eres administrador",
            ]);
        }

        // Validar los campos requeridos
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
            'precio' => 'required',
            'stock' => 'required',
            'nombre_categoria' => 'required',
        ]);

        // Verificar si la categoría existe en la base de datos
        $categoria = Categoria::where('nombre', $request->nombre_categoria)->first();
        if (!$categoria) {
            return response()->json([
                "status" => 0,
                "msg" => "La categoría no existe",
            ]);
        }

        // Crear el nuevo producto
        $producto = new Producto();
        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->precio = $request->precio;
        $producto->stock = $request->stock;
        $producto->categoria_id = $categoria->id;
        $producto->save();

        return response()->json([
            "status" => 1,
            "msg" => "¡Registro de producto exitoso!",
        ]);
    }

    public function update(Request $request, $id)
    {
        // Verificar si el usuario es un administrador
        if (Auth::user()->rol == 0) {
            return response()->json([
                "status" => 0,
                "msg" => "No eres administrador",
            ]);
        }

        // Verificar si el producto existe en la base de datos
        $producto = Producto::find($id);
        if (!$producto) {
            return response()->json([
                "status" => 0,
                "msg" => "El producto no existe",
            ]);
        }

        // Validar los campos requeridos
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
            'precio' => 'required',
            'stock' => 'required',
            'nombre_categoria' => 'required',
        ]);

        // Verificar si la categoría existe en la base de datos
        $categoria = Categoria::where('nombre', $request->nombre_categoria)->first();
        if (!$categoria) {
            return response()->json([
                "status" => 0,
                "msg" => "La categoría no existe",
            ]);
        }

        // Actualizar el producto
        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->precio = $request->precio;
        $producto->stock = $request->stock;
        $producto->categoria_id = $categoria->id;
        $producto->save();

        return response()->json([
            "status" => 1,
            "msg" => "¡Producto modificado!",
        ]);
    }

    public function delete(Request $request)
    {
        // Se verifica si el usuario que hace la petición es un administrador.
        if (Auth::user()->rol == 0) {
            // Si no es un administrador, se devuelve una respuesta indicando que no tiene permisos suficientes.
            return response()->json([
                "status" => 0,
                "msg" => "No eres admin",
            ]);
        } else {
            // Se crea una instancia del modelo Producto.
            $producto = new Producto();

            // Se busca el producto a eliminar en la base de datos a través del ID proporcionado en la petición.
            if ($producto = Producto::find($request->id)) {

                // Se elimina el producto de la base de datos.
                $producto->delete();

                // Se devuelve una respuesta indicando que el producto ha sido eliminado correctamente.
                return response()->json([
                    "status" => 1,
                    "msg" => "¡Producto eliminado!",
                ]);
            }
        }
    }
}
