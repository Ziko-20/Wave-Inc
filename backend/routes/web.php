<?php

use Illuminate\Support\Facades\Route;

// Minimal web routes — this app is API-only.
// Sanctum CSRF cookie endpoint is handled automatically via the api middleware.
Route::get('/', fn () => response()->json(['status' => 'GestionClients API is running']));
