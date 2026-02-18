<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('/dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('/profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
    
Route::get('/clients', App\Livewire\ClientManager::class)
    ->middleware(['auth'])
    ->name('clients.index');


require __DIR__.'/auth.php';
