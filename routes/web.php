<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pages\DashboardController;
use App\Http\Controllers\pages\PostController;

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
$controller_path = 'App\Http\Controllers';

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
  $controller_path = 'App\Http\Controllers';
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
  Route::get('/post/list', [PostController::class, 'index'])->name('post.list');
  Route::get('/post/add', [PostController::class, 'indexAdd'])->name('post.add');
  Route::get('/post/category', [PostController::class, 'indexCategory'])->name('post.category');
  Route::get('/post/get', [PostController::class, 'getData'])->name('post.get');
  Route::post('/post', $controller_path . '\pages\PostController@store')->name('post.store');
  Route::get('/page-2', $controller_path . '\pages\Page2@index')->name('pages-page-2');
});

Route::get('/', function () {
  return redirect()->route('dashboard');
});
