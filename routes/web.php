<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Agency\AgencyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (no login needed)
|--------------------------------------------------------------------------
*/

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/voitures', [PublicController::class, 'carsIndex'])->name('cars.index');
Route::get('/voitures/{car}', [PublicController::class, 'carShow'])->name('cars.show');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (login / register / logout)
|--------------------------------------------------------------------------
*/

Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
Route::post('/connexion', [AuthController::class, 'login'])->name('login.submit');

Route::get('/inscription', [AuthController::class, 'showRegister'])->name('register');
Route::post('/inscription', [AuthController::class, 'register'])->name('register.submit');

// logout must be POST for security (a GET logout link could be triggered by accident/attack)
Route::post('/deconnexion', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| CLIENT ROUTES (only logged-in users with role = client)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:client'])->prefix('client')->name('client.')->group(function () {

    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');

    Route::get('/reservations', [ClientController::class, 'bookingsIndex'])->name('bookings.index');
    Route::post('/reservations/{car}', [ClientController::class, 'storeBooking'])->name('bookings.store');
    Route::patch('/reservations/{booking}/annuler', [ClientController::class, 'cancelBooking'])->name('bookings.cancel');

    Route::post('/avis/{car}', [ClientController::class, 'storeReview'])->name('reviews.store');
});

/*
|--------------------------------------------------------------------------
| AGENCY ROUTES (only logged-in users with role = agency)
|--------------------------------------------------------------------------
*/

// this route is OUTSIDE the "agency.approved" middleware group
// because a pending agency still needs to be able to SEE the "please wait" page
Route::middleware(['auth', 'role:agency'])->prefix('agence')->name('agency.')->group(function () {
    Route::get('/en-attente', [AgencyController::class, 'pending'])->name('pending');
});

// these routes REQUIRE the agency to be approved first
Route::middleware(['auth', 'role:agency', 'agency.approved'])->prefix('agence')->name('agency.')->group(function () {

    Route::get('/dashboard', [AgencyController::class, 'dashboard'])->name('dashboard');

    // cars CRUD
    Route::get('/voitures', [AgencyController::class, 'carsIndex'])->name('cars.index');
    Route::get('/voitures/ajouter', [AgencyController::class, 'carsCreate'])->name('cars.create');
    Route::post('/voitures', [AgencyController::class, 'carsStore'])->name('cars.store');
    Route::get('/voitures/{car}/modifier', [AgencyController::class, 'carsEdit'])->name('cars.edit');
    Route::put('/voitures/{car}', [AgencyController::class, 'carsUpdate'])->name('cars.update');
    Route::delete('/voitures/{car}', [AgencyController::class, 'carsDestroy'])->name('cars.destroy');

    // bookings on agency's cars
    Route::get('/reservations', [AgencyController::class, 'bookingsIndex'])->name('bookings.index');
    Route::patch('/reservations/{booking}/confirmer', [AgencyController::class, 'bookingConfirm'])->name('bookings.confirm');
    Route::patch('/reservations/{booking}/terminer', [AgencyController::class, 'bookingComplete'])->name('bookings.complete');
    Route::patch('/reservations/{booking}/annuler', [AgencyController::class, 'bookingCancel'])->name('bookings.cancel');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (only logged-in users with role = admin)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // agencies management
    Route::get('/agences', [AdminController::class, 'agenciesIndex'])->name('agencies.index');
    Route::patch('/agences/{agency}/approuver', [AdminController::class, 'agencyApprove'])->name('agencies.approve');
    Route::patch('/agences/{agency}/rejeter', [AdminController::class, 'agencyReject'])->name('agencies.reject');

    // users management
    Route::get('/utilisateurs', [AdminController::class, 'usersIndex'])->name('users.index');
    Route::delete('/utilisateurs/{user}', [AdminController::class, 'userDestroy'])->name('users.destroy');

    // bookings overview
    Route::get('/reservations', [AdminController::class, 'bookingsIndex'])->name('bookings.index');
});
