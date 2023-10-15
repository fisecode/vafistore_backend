<?php

use Illuminate\Support\Facades\Route;

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

// pages
Route::get('/pages/misc-error', $controller_path . '\pages\MiscError@index')->name('pages-misc-error');

// authentication
Route::get('/auth/login-basic', $controller_path . '\authentications\LoginBasic@index')->name('auth-login-basic');
Route::get('/auth/register-basic', $controller_path . '\authentications\RegisterBasic@index')->name(
  'auth-register-basic'
);

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
  $controller_path = 'App\Http\Controllers';
  Route::get('/dashboard', $controller_path . '\pages\HomePage@index')->name('dashboard');
  Route::get('/post', $controller_path . '\pages\PostController@index')->name('post');
  Route::post('/post', $controller_path . '\pages\PostController@store')->name('post.store');
  Route::get('/page-2', $controller_path . '\pages\Page2@index')->name('pages-page-2');
});

Route::get('/', function () {
  return redirect()->route('dashboard');
});
