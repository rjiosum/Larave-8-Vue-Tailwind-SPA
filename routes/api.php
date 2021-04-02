<?php

use App\Http\Controllers\Api\Address\AddressController;
use App\Http\Controllers\Api\Article\ArticleController;
use App\Http\Controllers\Api\Country\CountryController;
use App\Http\Controllers\Api\Auth\{AuthenticatedSessionController,
    EmailVerificationNotificationController,
    NewPasswordController,
    PasswordResetLinkController,
    RegisteredUserController,
    VerifyEmailController};
use App\Http\Controllers\Api\Home\HomeController;
use App\Http\Controllers\Api\User\{AvatarController, PasswordController, ProfileController};
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Api\Auth', 'prefix' => 'auth'], function () {

    Route::middleware(['guest:api'])->group(function () {
        Route::post('register', [RegisteredUserController::class, 'store'])->name('register');
        Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login');
        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
        Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.reset');
    });

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:api')->name('logout');

    Route::middleware(['throttle:6,1'])->group(function () {
        Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])->middleware(['signed'])->name('verification.verify');
        Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->name('verification.send');
    });
});

Route::group(['namespace' => 'Api\User', 'prefix' => 'user'], function () {
    Route::get('profile', [ProfileController::class, 'index'])->name('user.profile');

    Route::middleware(['auth:api', 'throttle:5,1'])->group(function () {
        Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::patch('password', [PasswordController::class, 'update'])->name('password.update');
        Route::post('avatar', [AvatarController::class, 'update'])->name('avatar.update');
    });

});

Route::group(['namespace' => 'Api\Article', 'prefix' => 'user', 'middleware' => ['auth:api']], function () {
    Route::get('article', [ArticleController::class, 'index'])->name('article');
    Route::post('article', [ArticleController::class, 'store'])->name('article.store');
    Route::get('article/{slug}', [ArticleController::class, 'show'])->name('article.show');
    Route::delete('article/{uuid}', [ArticleController::class, 'destroy'])->name('article.destroy');
    Route::patch('article/{uuid}', [ArticleController::class, 'update'])->name('article.update');
});

Route::group(['namespace' => 'Api\Address', 'prefix' => 'user', 'middleware' => ['auth:api']], function () {
    Route::get('address', [AddressController::class, 'index'])->name('address');
    Route::post('address', [AddressController::class, 'store'])->name('address.store');
    Route::get('address/{uuid}', [AddressController::class, 'show'])->name('address.show');
    Route::delete('address/{uuid}', [AddressController::class, 'destroy'])->name('address.destroy');
    Route::patch('address/{uuid}', [AddressController::class, 'update'])->name('address.update');
});

Route::group(['namespace' => 'Api\Country', 'middleware' => ['auth:api']], function () {
    Route::get('country', [CountryController::class, 'index'])->name('country');
});

Route::group(['namespace' => 'Api\Home'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/{slug}', [HomeController::class, 'show'])->name('home.show');
});
