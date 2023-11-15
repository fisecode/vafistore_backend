<?php

use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\DashboardController;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\pages\PageController;
use App\Http\Controllers\posts\PostController;
use App\Http\Controllers\posts\PostCategoryController;
use App\Http\Controllers\api_management\ProviderController;
use App\Http\Controllers\product\GameController;
use App\Http\Controllers\settings\ServiceController;
use App\Http\Controllers\slide_show\SlideShowController;
use App\Http\Controllers\socials\SocialController;

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
  Route::group(['middleware' => ['allowed.role:super admin']], function () {
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
    Route::resource('/slide-list', SlideShowController::class);
    Route::get('/slide', [SlideShowController::class, 'SlideManagement'])->name('slide');
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
    Route::get('/setting/services/get/{providerId}/{jenis}', [ServiceController::class, 'get'])->name('get-service');
    Route::delete('/setting/services/{providerId}/{jenis}', [ServiceController::class, 'destroy'])->name(
      'delete-service'
    );
    //Product Game
    Route::resource('/product/game-list', GameController::class);
    Route::get('/product/game', [GameController::class, 'GameManagement'])->name('product-game');
  });
});

Route::get('/test', [MiscError::class, 'index'])->name('test');

Route::get('/', function () {
  return redirect()->route('dashboard');
});
