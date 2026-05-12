<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\LicenseController;
use App\Http\Controllers\Api\LicenseOfferController;
use App\Http\Controllers\Api\ManagerController;
use App\Http\Controllers\Api\StatsController;
use App\Http\Controllers\Api\ExportController;
use App\Http\Controllers\Api\MonEspaceController;
use App\Http\Controllers\Api\PaypalController;

/*
|--------------------------------------------------------------------------
| Public Auth Routes
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']); // admin-only in practice

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    /*
    |----------------------------------------------------------------------
    | Admin + Manager Routes
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin|manager')->group(function () {

        // Clients
        Route::get('/clients/export/csv', [ExportController::class, 'exportClientsCsv']);
        Route::get('/clients/{client}/export/pdf', [ExportController::class, 'exportClientPdf']);
        Route::put('/clients/{client}/relance', [ClientController::class, 'relance']);
        Route::apiResource('clients', ClientController::class);

        // Payments
        Route::get('/clients/{client}/payments', [PaymentController::class, 'index']);
        Route::post('/clients/{client}/payments', [PaymentController::class, 'store']);
        Route::put('/payments/{payment}', [PaymentController::class, 'update']);
        Route::delete('/payments/{payment}', [PaymentController::class, 'destroy']);

        // Licenses
        Route::get('/licenses', [LicenseController::class, 'index']);
        Route::post('/licenses', [LicenseController::class, 'store']);
        Route::delete('/licenses/{license}', [LicenseController::class, 'destroy']);
        Route::post('/clients/{client}/licenses', [LicenseController::class, 'assign']);

        // Stats / Dashboard
        Route::get('/stats', [StatsController::class, 'index']);
        Route::get('/stats/revenue', [StatsController::class, 'revenue']);
    });

    /*
    |----------------------------------------------------------------------
    | Admin-only Routes
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {

        // License Offers
        Route::apiResource('license-offers', LicenseOfferController::class)
            ->except(['show', 'update']);

        // Managers
        Route::get('/managers', [ManagerController::class, 'index']);
        Route::post('/managers', [ManagerController::class, 'store']);
        Route::delete('/managers/{user}', [ManagerController::class, 'destroy']);
    });

    /*
    |----------------------------------------------------------------------
    | Client Self-Service Routes
    |----------------------------------------------------------------------
    */
    Route::middleware('role:client')->group(function () {
        Route::get('/mon-espace', [MonEspaceController::class, 'index']);
        Route::get('/mon-espace/export/pdf', [MonEspaceController::class, 'exportPdf']);

        // PayPal (client purchases)
        Route::post('/boutique/checkout', [PaypalController::class, 'checkout']);
        Route::get('/boutique/success', [PaypalController::class, 'success']);
        Route::get('/boutique/cancel', [PaypalController::class, 'cancel']);
    });

    // License offers listing is public to authenticated clients too
    Route::get('/license-offers', [LicenseOfferController::class, 'index']);
});
