<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class carritoController extends Controller
{
    public function agregarProducto(Request $request)
    {
        // Validar los datos enviados
        $request->validate([
            'id' => 'required',
            'stock' => 'required|numeric|min:1'
        ]);

        // Buscar el producto en la base de datos
        $producto = Producto::find($request->id);

        // Si el producto no existe, retornar un error
        if (!$producto) {
            return response()->json([
                'mensaje' => 'El producto no existe'
            ], 400);
        }

        // Obtener la cantidad solicitada del producto
        $stock = $request->stock;

        // Verificar si hay suficiente stock para el producto
        if ($producto->stock < $stock) {
            return response()->json([
                'mensaje' => 'No hay suficiente stock para el producto'
            ], 400);
        }

        // Obtener el usuario autenticado y su carrito asociado
        $user = Auth::user();
        $carrito = $user->carrito ?? new Carrito(['user_id' => $user->id]);

        // Obtener la cantidad actual del producto en el carrito
        $cantidadActual = $carrito->productos()
            ->where('id', $producto->id)
            ->value('cantidad');

        // Calcular la nueva cantidad del producto en el carrito
        $nuevaCantidad = $cantidadActual + $stock;

        // Si el producto ya estaba en el carrito, actualizar su cantidad
        if ($cantidadActual) {
            $carrito->productos()->updateExistingPivot(
                $producto->id,
                ['cantidad' => $nuevaCantidad]
            );
        } else { // Si el producto no estaba en el carrito, agregarlo
            $carrito->productos()->attach($producto->id, [
                'cantidad' => $nuevaCantidad,
                'precio' => $producto->precio
            ]);
        }

        // Actualizar el stock del producto en la base de datos
        $producto->decrement('stock', $stock);

        // Retornar una respuesta satisfactoria
        return response()->json([
            'mensaje' => 'Producto agregado al carrito exitosamente'
        ]);
    }

    public function eliminarProducto(Request $request)
    {
        // Validar los datos enviados
        $request->validate([
            'id' => 'required'
        ]);

        // Obtener el usuario autenticado y su carrito asociado
        $user = Auth::user();
        $carrito = $user->carrito;

        // Si el usuario no tiene carrito, retornar un error
        if (!$carrito) {
            return response()->json([
                'mensaje' => 'El carrito está vacío'
            ], 400);
        }

        // Buscar el producto en el carrito
        $producto = $carrito->productos()->find($request->id);

        // Si el producto no está en el carrito, retornar un error
        if (!$producto) {
            return response()->json([
                'mensaje' => 'El producto no está en el carrito'
            ], 400);
        }

        // Eliminar el producto del carrito
        $carrito->productos()->detach($producto->id);

        // Incrementar el stock del producto en la base de datos
        $producto->increment('stock', $producto->pivot->cantidad);

        return response()->json([
            'mensaje' => 'Producto eliminado del carrito exitosamente'
        ]);
    }

    public function vaciarCarrito()
    {
        // Obtener usuario autenticado y carrito asociado
        $user = Auth::user();
        $carrito = $user->carrito;

        // Si no hay carrito para el usuario, no se puede vaciar
        if (!$carrito) {
            return response()->json([
                'mensaje' => 'El carrito está vacío'
            ], 400);
        }

        // Recorrer los productos del carrito y actualizar el stock de cada uno
        foreach ($carrito->productos as $producto) {
            $cantidad = $producto->pivot->stock;
            $producto->stock += $cantidad;
            $producto->save();
        }

        // Eliminar todos los productos del carrito
        $carrito->productos()->detach();

        return response()->json([
            'mensaje' => 'Carrito vaciado exitosamente'
        ]);
    }

    public function index()
    {
        $user = Auth::user();
        $carrito = $user->carrito;

        if (!$carrito) {
            $productos = collect();
            $total = 0;
        } else {
            $productos = $carrito->productos()->get();
            $total = $carrito->productos()->sum(function ($producto) {
                return $producto->precio * $producto->pivot->cantidad;
            });
        }

        return view('carrito', compact('productos', 'total'));
    }
}
