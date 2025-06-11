<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CapituloHistoriaController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HistoriaController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FollowController;

use Illuminate\Support\Facades\Auth;
Route::get('/', [InicioController::class, 'index'])->name('inicio');

// Rutas de vistas estáticas solo si no se accede desde controlador
Route::view('/registro', 'registro')->name('registro.view');
Route::view('/login', 'login')->name('login.view');

// Registro
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Login / Logout
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas públicas de historias y capítulos
Route::get('/historias/create', [HistoriaController::class, 'create'])
    ->middleware('auth') // Esta ya estaba protegida
    ->name('historias.create');
Route::get('/historias/{id}', [HistoriaController::class, 'show'])->name('historias.show');
Route::get('/historias/{id}/capitulos', [CapituloHistoriaController::class, 'index'])->name('capitulos.index');
Route::get('/capitulos/{id}', [CapituloHistoriaController::class, 'show'])->name('capitulos.show');
Route::get('/capitulos/{id}/comentarios', [ComentarioController::class, 'getByCapitulo'])->name('comentarios.byCapitulo');
Route::get('/capitulos/{id}/likes', [LikeController::class, 'getByCapitulo'])->name('likes.byCapitulo');

// Ruta para el buscador
Route::get('/search', [SearchController::class, 'index'])->name('search.results');

Route::middleware('auth')->group(function () {
    Route::get('/historias', [HistoriaController::class, 'index'])->name('historias.index');

    // Likes en capítulos
    Route::post('/capitulos/{id}/likes/toggle', [LikeController::class, 'toggleCapituloLike'])->name('capitulos.likes.toggle');

    // Likes en comentarios
    Route::post('/comentarios/{id}/likes/toggle', [LikeController::class, 'toggleComentarioLike'])->name('comentarios.likes.toggle');

    // Rutas de PERFIL DE USUARIO (Edición y Actualización)
    Route::get('/perfil/editar', [UserController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [UserController::class, 'update'])->name('perfil.update');
    Route::get('/perfil', [UserController::class, 'perfil'])->name('perfil');
    Route::get('/perfil/{id}', [UserController::class, 'mostrarPerfil'])->name('perfil.show');

    // RUTAS PARA FOLLOWS
    Route::post('/users/{user}/follow', [FollowController::class, 'toggleFollow'])->name('users.toggleFollow');

    // Crear, editar, eliminar historias
    Route::post('/historias', [HistoriaController::class, 'store'])->name('historias.store');
    Route::get('/historias/{id}/edit', [HistoriaController::class, 'edit'])->name('historias.edit');
    Route::put('/historias/{id}', [HistoriaController::class, 'update'])->name('historias.update');
    Route::delete('/historias/{id}', [HistoriaController::class, 'destroy'])->name('historias.destroy');

    // Crear capítulos (nota: 'create' personalizado fuera del resource)
    Route::get('capitulos/create/{historiaId}', [CapituloHistoriaController::class, 'create'])->name('capitulos.create');
    Route::post('capitulos', [CapituloHistoriaController::class, 'store'])->name('capitulos.store');
    Route::get('capitulos/{id}/edit', [CapituloHistoriaController::class, 'edit'])->name('capitulos.edit');
    Route::put('capitulos/{id}', [CapituloHistoriaController::class, 'update'])->name('capitulos.update');
    Route::delete('capitulos/{id}', [CapituloHistoriaController::class, 'destroy'])->name('capitulos.destroy');

    // Comentarios y Likes (requieren login)
    Route::resource('comentarios', ComentarioController::class);
    Route::resource('likes', LikeController::class);
});
