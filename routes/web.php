<?php

use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;
/* use App\Http\Controllers\ClientController; */
use App\Livewire\ClientTrackDashboard;
use App\Livewire\ClientCrud;
use App\Livewire\ChartPaiments;

Route::get('language/{locale}', [LanguageController::class, 'switchLang'])->name('language.switch');

Route::redirect('/', '/login');

// Routes Back-Office réservées aux Admin et Managers
Route::middleware(['auth', 'verified', 'role:admin|manager'])->group(function () {
    Route::get('/dashboard', ClientTrackDashboard::class)->name('dashboard');
    Route::get('/chart', ChartPaiments::class)->name('chart');
    Route::get('/clients', ClientCrud::class)->name('clients.index');
});

// Route Espace Client réservée aux Clients
Route::middleware(['auth', 'verified', 'role:client'])->group(function () {
    Route::get('/hub', \App\Livewire\ClientHub::class)->name('client.hub');
    Route::get('/mon-espace', \App\Livewire\ClientPortal::class)->name('client.portal');
    Route::get('/boutique', \App\Livewire\LicenseShop::class)->name('license.shop');
    
    
    // PayPal Routes
    Route::get('/paypal/create/{offer}', [\App\Http\Controllers\PaypalController::class, 'createPayment'])->name('paypal.create');
    Route::get('/paypal/capture', [\App\Http\Controllers\PaypalController::class, 'capturePayment'])->name('paypal.capture');
    Route::get('/paypal/cancel', [\App\Http\Controllers\PaypalController::class, 'cancelPayment'])->name('paypal.cancel');
});

Route::view('/profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
    //changer la langue





/* Route::get('/clients',[ClientController::class ,'index'])
->middleware(['auth']);
//->name('clients.index'); */
//Route::get('/clients', [ClientController::class, 'index'])
   // 
   // 

//Route::get('/ajouterrr',[ClientController::class,'ajout'])    ;

// Routes admin uniquement
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/managers', \App\Livewire\GestionManagers::class)->name('managers.index');
});
require __DIR__.'/auth.php';
