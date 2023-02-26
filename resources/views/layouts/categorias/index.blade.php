@extends('layouts.app')

@section('content')

<h1>Listado de Categorías</h1>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th><a href="{{ URL::previous() }}" class="btn btn-secondary">Volver</a></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categorias as $categoria)
        <tr>
            <td>{{ $categoria->nombre }}</td>
            <td>{{ $categoria->descripcion }}</td>
        </tr>
        @endforeach
    </tbody>
</table>



@if(Auth::user() && Auth::user()->admin == 1)
<!-- Modal para agregar/editar categorías -->

<div class="container">
    <div id="modal-categorias" tabindex="-1" role="dialog" aria-labelledby="modal-categorias-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('categorias.insert') }}" method="POST" id="form-categoria">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-categorias-label">Agregar categoría</h5>
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="btn-guardar-categoria">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nombre</th>
                <th scope="col">Descripción</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categorias as $categoria)
            <tr>
                <th scope="row">{{ $categoria->id_categoria }}</th>
                <td>{{ $categoria->nombre }}</td>
                <td>{{ $categoria->descripcion }}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-categorias" onclick="editarCategoria('{{ $categoria->id_categoria }}', '{{ $categoria->nombre }}', '{{ $categoria->descripcion }}')">Editar</button>
                    <form action="{{ route('categorias.delete') }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="id_categoria" value="{{ $categoria->id_categoria }}">
                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endif



@endsection

<script>
    function editarCategoria(id_categoria, nombre, descripcion) {
        document.getElementById('modal-categorias-label').innerHTML = 'Editar categoría';
        document.getElementById('form-categoria').setAttribute('action', '/categorias/update/' + id_categoria);
        document.getElementById('nombre').value = nombre;
        document.getElementById('descripcion').value = descripcion;
        document.getElementById
    }
</script>