<?php

use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;
/* use App\Http\Controllers\ClientController; */
use App\Livewire\ClientTrackDashboard;
use App\Livewire\ClientCrud;
use App\Livewire\ChartPaiments;

Route::get('language/{locale}', [LanguageController::class, 'switchLang'])->name('language.switch');

Route::redirect('/', '/login');

Route::get('/dashboard', ClientTrackDashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::get('/chart', ChartPaiments::class)
    ->middleware(['auth', 'verified'])
    ->name('chart');

Route::view('/profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/clients', ClientCrud::class)
    ->middleware(['auth', 'verified'])
    ->name('clients.index');
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
