<?php

use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\DashboardController;
use App\Http\Controllers\pages\PageController;
use App\Http\Controllers\posts\PostController;
use App\Http\Controllers\posts\PostCategoryController;
use App\Http\Controllers\api_management\ProviderController;
use App\Http\Controllers\product\EntertainmentController;
use App\Http\Controllers\product\GameController;
use App\Http\Controllers\product\PostpaidController;
use App\Http\Controllers\product\PrepaidController;
use App\Http\Controllers\product\SocialMediaController;
use App\Http\Controllers\product\VoucherController;
use App\Http\Controllers\settings\ServiceController;
use App\Http\Controllers\slide_show\SlideShowController;
use App\Http\Controllers\socials\SocialController;
use App\Http\Controllers\ViewController;
use Laravel\Fortify\Features;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\ConfirmablePasswordController;
use Laravel\Fortify\Http\Controllers\ConfirmedPasswordStatusController;
use Laravel\Fortify\Http\Controllers\ConfirmedTwoFactorAuthenticationController;
use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController;
use Laravel\Fortify\Http\Controllers\EmailVerificationPromptController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\ProfileInformationController;
use Laravel\Fortify\Http\Controllers\RecoveryCodeController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticationController;
use Laravel\Fortify\Http\Controllers\TwoFactorQrCodeController;
use Laravel\Fortify\Http\Controllers\TwoFactorSecretKeyController;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;
use Laravel\Fortify\RoutePath;

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
    Route::group(['prefix' => 'dashboard'], function () {
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
      //Game Product
      Route::resource('/product/game-list', GameController::class);
      Route::get('/product/game', [GameController::class, 'GameManagement'])->name('product-game');
      Route::post('/product/game/save-bulk-edit/', [GameController::class, 'saveBulkEdit'])->name('save-bulk-edit');
      //Prepaid Product
      Route::resource('/product/prepaid-list', PrepaidController::class);
      Route::get('/product/prepaid', [PrepaidController::class, 'PrepaidManagement'])->name('product-prepaid');
      Route::post('/product/prepaid/save-bulk-edit/', [PrepaidController::class, 'saveBulkEdit'])->name('save-bulk-edit');
      //Postpaid Product
      Route::resource('/product/postpaid-list', PostpaidController::class);
      Route::get('/product/postpaid', [PostpaidController::class, 'PostpaidManagement'])->name('product-postpaid');
      Route::post('/product/postpaid/save-bulk-edit/', [PostpaidController::class, 'saveBulkEdit'])->name('save-bulk-edit');
      //Voucher Product
      Route::resource('/product/voucher-list', VoucherController::class);
      Route::get('/product/voucher', [VoucherController::class, 'VoucherManagement'])->name('product-voucher');
      Route::post('/product/voucher/save-bulk-edit/', [VoucherController::class, 'saveBulkEdit'])->name('save-bulk-edit');
      //Entertainment Product
      Route::resource('/product/entertainment-list', EntertainmentController::class);
      Route::get('/product/entertainment', [EntertainmentController::class, 'EntertainmentManagement'])->name('product-entertainment');
      Route::post('/product/entertainment/save-bulk-edit/', [EntertainmentController::class, 'saveBulkEdit'])->name('save-bulk-edit');
      //Social Media Product
      Route::resource('/product/social-media-list', SocialMediaController::class);
      Route::get('/product/social-media', [SocialMediaController::class, 'SocialMediaManagement'])->name('product-social-media');
      Route::post('/product/social-media/save-bulk-edit/', [SocialMediaController::class, 'saveBulkEdit'])->name('save-bulk-edit');
    });
  });
});

//Home
Route::get('/', function () {
  return view('content.front-page.landing-page');
})->name('home');

//Auth
Route::group(['middleware' => config('fortify.middleware', ['web'])], function () {
  $enableViews = config('fortify.views', true);

  // Authentication...
  if ($enableViews) {
    Route::get(RoutePath::for('login', '/admin/login'), [AuthenticatedSessionController::class, 'create'])
      ->middleware(['guest:' . config('fortify.guard')])
      ->name('login.admin');
    Route::get('/auth/login', [ViewController::class, 'loginMember'])
      ->middleware(['guest:' . config('fortify.guard')])
      ->name('login.member');
  }

  $limiter = config('fortify.limiters.login');
  $twoFactorLimiter = config('fortify.limiters.two-factor');
  $verificationLimiter = config('fortify.limiters.verification', '6,1');

  Route::post(RoutePath::for('login', '/auth/login'), [AuthenticatedSessionController::class, 'store'])
    ->middleware(array_filter([
      'guest:' . config('fortify.guard'),
      $limiter ? 'throttle:' . $limiter : null,
    ]));
  Route::post(RoutePath::for('login', '/admin/login'), [AuthenticatedSessionController::class, 'store'])
    ->middleware(array_filter([
      'guest:' . config('fortify.guard'),
      $limiter ? 'throttle:' . $limiter : null,
    ]));

  Route::post(RoutePath::for('logout', '/logout'), [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

  // Password Reset...
  if (Features::enabled(Features::resetPasswords())) {
    if ($enableViews) {
      Route::get(RoutePath::for('password.request', '/forgot-password'), [PasswordResetLinkController::class, 'create'])
        ->middleware(['guest:' . config('fortify.guard')])
        ->name('password.request');

      Route::get(RoutePath::for('password.reset', '/reset-password/{token}'), [NewPasswordController::class, 'create'])
        ->middleware(['guest:' . config('fortify.guard')])
        ->name('password.reset');
    }

    Route::post(RoutePath::for('password.email', '/forgot-password'), [PasswordResetLinkController::class, 'store'])
      ->middleware(['guest:' . config('fortify.guard')])
      ->name('password.email');

    Route::post(RoutePath::for('password.update', '/reset-password'), [NewPasswordController::class, 'store'])
      ->middleware(['guest:' . config('fortify.guard')])
      ->name('password.update');
  }

  // Registration...
  if (Features::enabled(Features::registration())) {
    if ($enableViews) {
      Route::get(RoutePath::for('register', '/register'), [RegisteredUserController::class, 'create'])
        ->middleware(['guest:' . config('fortify.guard')])
        ->name('register');
    }

    Route::post(RoutePath::for('register', '/register'), [RegisteredUserController::class, 'store'])
      ->middleware(['guest:' . config('fortify.guard')]);
  }

  // Email Verification...
  if (Features::enabled(Features::emailVerification())) {
    if ($enableViews) {
      Route::get(RoutePath::for('verification.notice', '/email/verify'), [EmailVerificationPromptController::class, '__invoke'])
        ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')])
        ->name('verification.notice');
    }

    Route::get(RoutePath::for('verification.verify', '/email/verify/{id}/{hash}'), [VerifyEmailController::class, '__invoke'])
      ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard'), 'signed', 'throttle:' . $verificationLimiter])
      ->name('verification.verify');

    Route::post(RoutePath::for('verification.send', '/email/verification-notification'), [EmailVerificationNotificationController::class, 'store'])
      ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard'), 'throttle:' . $verificationLimiter])
      ->name('verification.send');
  }

  // Profile Information...
  if (Features::enabled(Features::updateProfileInformation())) {
    Route::put(RoutePath::for('user-profile-information.update', '/user/profile-information'), [ProfileInformationController::class, 'update'])
      ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')])
      ->name('user-profile-information.update');
  }

  // Passwords...
  if (Features::enabled(Features::updatePasswords())) {
    Route::put(RoutePath::for('user-password.update', '/user/password'), [PasswordController::class, 'update'])
      ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')])
      ->name('user-password.update');
  }

  // Password Confirmation...
  if ($enableViews) {
    Route::get(RoutePath::for('password.confirm', '/user/confirm-password'), [ConfirmablePasswordController::class, 'show'])
      ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')]);
  }

  Route::get(RoutePath::for('password.confirmation', '/user/confirmed-password-status'), [ConfirmedPasswordStatusController::class, 'show'])
    ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')])
    ->name('password.confirmation');

  Route::post(RoutePath::for('password.confirm', '/user/confirm-password'), [ConfirmablePasswordController::class, 'store'])
    ->middleware([config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')])
    ->name('password.confirm');

  // Two Factor Authentication...
  if (Features::enabled(Features::twoFactorAuthentication())) {
    if ($enableViews) {
      Route::get(RoutePath::for('two-factor.login', '/two-factor-challenge'), [TwoFactorAuthenticatedSessionController::class, 'create'])
        ->middleware(['guest:' . config('fortify.guard')])
        ->name('two-factor.login');
    }

    Route::post(RoutePath::for('two-factor.login', '/two-factor-challenge'), [TwoFactorAuthenticatedSessionController::class, 'store'])
      ->middleware(array_filter([
        'guest:' . config('fortify.guard'),
        $twoFactorLimiter ? 'throttle:' . $twoFactorLimiter : null,
      ]));

    $twoFactorMiddleware = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')
      ? [config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard'), 'password.confirm']
      : [config('fortify.auth_middleware', 'auth') . ':' . config('fortify.guard')];

    Route::post(RoutePath::for('two-factor.enable', '/user/two-factor-authentication'), [TwoFactorAuthenticationController::class, 'store'])
      ->middleware($twoFactorMiddleware)
      ->name('two-factor.enable');

    Route::post(RoutePath::for('two-factor.confirm', '/user/confirmed-two-factor-authentication'), [ConfirmedTwoFactorAuthenticationController::class, 'store'])
      ->middleware($twoFactorMiddleware)
      ->name('two-factor.confirm');

    Route::delete(RoutePath::for('two-factor.disable', '/user/two-factor-authentication'), [TwoFactorAuthenticationController::class, 'destroy'])
      ->middleware($twoFactorMiddleware)
      ->name('two-factor.disable');

    Route::get(RoutePath::for('two-factor.qr-code', '/user/two-factor-qr-code'), [TwoFactorQrCodeController::class, 'show'])
      ->middleware($twoFactorMiddleware)
      ->name('two-factor.qr-code');

    Route::get(RoutePath::for('two-factor.secret-key', '/user/two-factor-secret-key'), [TwoFactorSecretKeyController::class, 'show'])
      ->middleware($twoFactorMiddleware)
      ->name('two-factor.secret-key');

    Route::get(RoutePath::for('two-factor.recovery-codes', '/user/two-factor-recovery-codes'), [RecoveryCodeController::class, 'index'])
      ->middleware($twoFactorMiddleware)
      ->name('two-factor.recovery-codes');

    Route::post(RoutePath::for('two-factor.recovery-codes', '/user/two-factor-recovery-codes'), [RecoveryCodeController::class, 'store'])
      ->middleware($twoFactorMiddleware);
  }
});

// Route::middleware('guest')->group(function () {
//   Route::redirect('/dashboard', '/')->name('dashboard')->middleware('guest');
// });
