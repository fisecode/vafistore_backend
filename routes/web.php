<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pages\DashboardController;
use App\Http\Controllers\pages\PostController;
use App\Http\Controllers\pages\PostCategoryController;

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
  //Post
  Route::get('/posts', [PostController::class, 'PostManagement'])->name('post');
  Route::resource('/post-list', PostController::class);
  Route::get('/post/{id}/edit', [PostController::class, 'edit'])->name('post.edit');
  Route::post('/post/{id}/update', [PostController::class, 'update'])->name('post.update');
  Route::get('/post/add', [PostController::class, 'create'])->name('post.add');
  Route::post('/post', [PostController::class, 'store'])->name('post.store');
  //Post Category
  Route::resource('/post-category', PostCategoryController::class);
  Route::get('/post/category', [PostCategoryController::class, 'PostCategoryManagement'])->name('post.category');
  Route::get('/post/category/get', [PostCategoryController::class, 'get'])->name('post.category.get');

  // Route::get('/post/list', [PostController::class, 'index'])->name('post.list');
  // Route::delete('/post/{id}', [PostController::class, 'destroy'])->name('post.destroy');
  // Route::get('/post/delete/{id}', [PostController::class, 'delete'])->name('post.delete');
  // Route::get('/post/category', [PostController::class, 'indexCategory'])->name('post.category');
  // Route::get('/post/get', [PostController::class, 'getData'])->name('post.get');
  Route::get('/page-2', $controller_path . '\pages\Page2@index')->name('pages-page-2');
});

Route::get('/', function () {
  return redirect()->route('dashboard');
});
