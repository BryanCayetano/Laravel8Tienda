<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\productoController;
use App\Http\Controllers\categoriaController;
use App\Http\Controllers\carritoController;
use App\Http\Controllers\Auth\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


//PRODUCTOS//
Route::get('/productos', [productoController::class, 'index'])->name('productos');
Route::post('/productos/insert', [productoController::class, 'insert'])->name('productos.insert');
Route::put('/categorias/update/{id_producto}', [productoController::class, 'update'])->name('productos.update');
Route::delete('/productos/{producto}', [productoController::class, 'delete'])->name('productos.delete');

//CATEGORIAS//
Route::get('/categorias', [categoriaController::class, 'index'])->name('categorias');
Route::post('/categorias/insert', [categoriaController::class, 'insert'])->name('categorias.insert');
Route::put('/categorias/update/{id_categoria}', [categoriaController::class, 'update'])->name('categorias.update');
Route::delete('/categorias/delete', [categoriaController::class, 'delete'])->name('categorias.delete');

//CARRITO//
Route::get('/carrito', [carritoController::class, 'index'])->name('carrito');
Route::post('/carrito/agregar-producto', [carritoController::class, 'agregarProducto'])->name('carrito.agregarProducto');
Route::delete('/carrito/eliminar-producto', [carritoController::class, 'eliminarProducto'])->name('carrito.eliminarProducto');
Route::post('/carrito/vaciar', [carritoController::class, 'vaciarCarrito'])->name('carrito.vaciar');

//ADMINISTRACION//

Route::put('/remove-admin/{id}', [UserController::class, 'removeAdmin'])->name('removeAdmin');
Route::put('/make-admin/{id}', [UserController::class, 'makeAdmin'])->name('makeAdmin');


