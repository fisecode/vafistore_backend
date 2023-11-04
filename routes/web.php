<?php

use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pages\DashboardController;
use App\Http\Controllers\pages\PageController;
use App\Http\Controllers\pages\PostController;
use App\Http\Controllers\pages\PostCategoryController;
use App\Http\Controllers\pages\ProviderController;
use App\Http\Controllers\pages\ServiceController;
use App\Http\Controllers\pages\SlideController;
use App\Http\Controllers\pages\SocialController;

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

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
  // Rute yang memerlukan peran "super admin"
  Route::group(['middleware' => ['allowed.role:admin,super admin']], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    //Post
    Route::get('/post/list', [PostController::class, 'PostManagement'])->name('post-list');
    Route::resource('/post-list', PostController::class);
    Route::get('/post/{id}/edit', [PostController::class, 'edit'])->name('post.edit');
    Route::post('/post/{id}/update', [PostController::class, 'update'])->name('post.update');
    Route::get('/post/add', [PostController::class, 'create'])->name('post-add');
    Route::post('/post', [PostController::class, 'store'])->name('post.store');
    //Post Category
    Route::resource('/post-category', PostCategoryController::class);
    Route::get('/post/category', [PostCategoryController::class, 'PostCategoryManagement'])->name('post-category');
    Route::get('/post/category/get', [PostCategoryController::class, 'get'])->name('post.category.get');
    //Pages
    Route::resource('/page-list', PageController::class);
    Route::get('page', [PageController::class, 'PageManagement'])->name('page');
    Route::get('page/add', [PageController::class, 'create'])->name('page.add');
    Route::get('page/{id}/edit', [PageController::class, 'edit'])->name('page.edit');
    //slide
    Route::resource('/slide-list', SlideController::class);
    Route::get('/slide', [SlideController::class, 'SlideManagement'])->name('slide');
    //socials
    Route::resource('/social-list', SocialController::class);
    Route::get('/socials', [SocialController::class, 'SocialManagement'])->name('socials');
    //member
    Route::get('/member/basic', [MemberController::class, 'index'])->name('member-basic');
    //provider
    Route::resource('/api/provider-list', ProviderController::class);
    Route::get('/api/provider', [ProviderController::class, 'ProviderManagement'])->name('api-provider');
    //Services
    Route::resource('/setting/services', ServiceController::class);
    Route::get('/setting/services', [ServiceController::class, 'index'])->name('setting-services');
  });
});

Route::get('/', function () {
  return redirect()->route('dashboard');
});
