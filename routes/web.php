<?php

use Illuminate\Support\Facades\Route;
/* use App\Http\Controllers\ClientController; */
use App\Livewire\ClientTrackDashboard;
use App\Livewire\ClientCrud;

Route::view('/', 'welcome');


Route::get('/dashboard', ClientTrackDashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('/profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/clients', ClientCrud::class)
    ->middleware(['auth', 'verified'])
    ->name('clients.index');




/* Route::get('/clients',[ClientController::class ,'index'])
->middleware(['auth']);
//->name('clients.index'); */
//Route::get('/clients', [ClientController::class, 'index'])
   // 
   // 

//Route::get('/ajouterrr',[ClientController::class,'ajout'])    ;

require __DIR__.'/auth.php';
