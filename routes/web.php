<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
});

// Method 3
Route::inertia('/about', 'About', [
    'user' => 'Tameem' // props
]);