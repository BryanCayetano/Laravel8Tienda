@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Carrito de compras</h1>

    @if ($productos->isEmpty())
    <p>No hay productos en el carrito</p>
    @else
    <table class="table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productos as $producto)
            <tr>
                <td>{{ $producto->nombre }}</td>
                <td>
                    <form action="{{ route('carrito.actualizar', $producto->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <input type="number" name="cantidad" value="{{ $producto->pivot->cantidad }}" min="1" max="{{ $producto->stock }}" required>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </form>
                </td>
                <td>${{ $producto->precio }}</td>
                <td>${{ $producto->precio * $producto->pivot->cantidad }}</td>
                <td>
                    <form action="{{ route('carrito.eliminar', $producto->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <form action="{{ route('carrito.vaciar') }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Vaciar carrito</button>
    </form>

    @endif
</div>
@endsection