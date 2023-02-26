@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Productos</h1>
    @if (Auth::user()->rol == 1)
    <button type="button" class="btn btn-success btn-agregar">Agregar</button>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productos as $producto)
            <tr>
                <td>{{ $producto->nombre }}</td>
                <td>{{ $producto->descripcion }}</td>
                <td>{{ $producto->precio }}</td>
                <td>{{ $producto->stock }}</td>
                <td>{{ $producto->nombre_categoria }}</td>
                <td>
                    @if (Auth::user()->admin == 1)
                    <form action="{{ route('productos.delete', ['producto' => $producto->id]) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>

                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if(Auth::user()->admin == 0)
    <a href="{{ URL::previous() }}" class="btn btn-secondary">Volver</a>
    @endif

    @if(Auth::user()->admin == 1)

    <!-- Modal para agregar/editar producto -->

    <form action="{{ route('productos.insert') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title" id="modal-producto-label">Agregar producto</h5>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre">
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion"></textarea>
            </div>
            <div class="form-group">
                <label for="precio">Precio</label>
                <input type="number" class="form-control" id="precio" name="precio">
            </div>
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" min="1">
            </div>
            <div class="form-group">
                <label for="categoria_id">Categoría</label>
                <select class="form-control" id="nombre_categoria" name="nombre_categoria">
                    @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->nombre }}">{{ $categoria->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <a href="{{ URL::previous() }}" class="btn btn-secondary">Volver</a>
            <button type="submit" class="btn btn-primary" id="btn-guardar-producto">Guardar</button>
        </div>
    </form>

    @endif
</div>