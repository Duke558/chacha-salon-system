<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Admin\BookingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [ReviewController::class, 'index'])->name('about');
Route::get('/services', [ServiceController::class, 'index'])->name('services');

Route::get('/reviews/load-more', [ReviewController::class, 'loadMore'])
    ->name('reviews.loadMore');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])
        ->name('profile.photo.update');

    /*
    |--------------------------------------------------------------------------
    | Feedback (User)
    |--------------------------------------------------------------------------
    */

    Route::get('/feedback/create/{booking}', [FeedbackController::class, 'create'])
        ->name('feedback.create');

    Route::post('/feedback', [FeedbackController::class, 'store'])
        ->name('feedback.store');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware(['admin'])->prefix('admin')->group(function () {

        Route::get('/', [AdminController::class, 'index'])
            ->name('admin.dashboard');

        /*
        |--------------------------------------------------------------------------
        | Admin Bookings (Fixed)
        |--------------------------------------------------------------------------
        */

        Route::get('/bookings', [BookingsController::class, 'index'])
            ->name('admin.bookings');

        // ✅ Added missing show route for eye icon
        Route::get('/bookings/{booking}', [BookingsController::class, 'show'])
            ->name('admin.bookings.show');

        Route::post('/bookings/{booking}/confirm', [BookingsController::class, 'confirm'])
            ->name('admin.bookings.confirm');

        Route::post('/bookings/{booking}/complete', [BookingsController::class, 'complete'])
            ->name('admin.bookings.complete');

        Route::post('/bookings/{booking}/cancel', [BookingsController::class, 'cancel'])
            ->name('admin.bookings.cancel');

        Route::post('/bookings/{booking}/reject', [BookingsController::class, 'reject'])
            ->name('admin.bookings.reject');

        /*
        |--------------------------------------------------------------------------
        | Services Management
        |--------------------------------------------------------------------------
        */

        Route::get('/services', [AdminController::class, 'services'])
            ->name('admin.services');

        Route::get('/services/create', [AdminController::class, 'createService'])
            ->name('admin.services.create');

        Route::post('/services', [AdminController::class, 'storeService'])
            ->name('admin.services.store');

        Route::get('/services/{service}/edit', [AdminController::class, 'editService'])
            ->name('admin.services.edit');

        Route::put('/services/{service}', [AdminController::class, 'updateService'])
            ->name('admin.services.update');

        Route::delete('/services/{service}', [AdminController::class, 'destroyService'])
            ->name('admin.services.destroy');

        /*
        |--------------------------------------------------------------------------
        | Feedback Management
        |--------------------------------------------------------------------------
        */

        Route::get('/feedbacks', [App\Http\Controllers\Admin\FeedbackController::class, 'index'])
            ->name('admin.feedback.index');

        Route::patch('/feedbacks/{feedback}/toggle-publish', [App\Http\Controllers\Admin\FeedbackController::class, 'togglePublish'])
            ->name('admin.feedback.toggle-publish');

        /*
        |--------------------------------------------------------------------------
        | Users Management
        |--------------------------------------------------------------------------
        */

        Route::get('/users', [AdminController::class, 'users'])
            ->name('admin.users.index');

        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])
            ->name('admin.users.edit');

        Route::put('/users/{user}', [AdminController::class, 'updateUser'])
            ->name('admin.users.update');

        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])
            ->name('admin.users.destroy');

        /*
        |--------------------------------------------------------------------------
        | Categories Management
        |--------------------------------------------------------------------------
        */

        Route::get('/categories', [CategoryController::class, 'index'])
            ->name('admin.categories');

        Route::get('/categories/create', [CategoryController::class, 'create'])
            ->name('admin.categories.create');

        Route::post('/categories', [CategoryController::class, 'store'])
            ->name('admin.categories.store');

        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])
            ->name('admin.categories.edit');

        Route::put('/categories/{category}', [CategoryController::class, 'update'])
            ->name('admin.categories.update');

        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
            ->name('admin.categories.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Booking (User Side)
    |--------------------------------------------------------------------------
    */

    Route::get('/booking', [BookingController::class, 'index'])
        ->name('booking');

    Route::post('/bookings', [BookingController::class, 'store'])
        ->name('bookings.store');

    Route::get('/booking/{id}/payment', [BookingController::class, 'showPayment'])
        ->name('booking.payment');

    Route::post('/booking/{id}/payment', [BookingController::class, 'processPayment'])
        ->name('booking.payment.process');

    Route::get('/booking/{id}/payment/success', [BookingController::class, 'paymentSuccess'])
        ->name('booking.payment.success');

    Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel'])
        ->name('bookings.cancel');

    Route::post('/bookings/{id}/reschedule', [BookingController::class, 'reschedule'])
        ->name('bookings.reschedule');

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__ . '/auth.php';