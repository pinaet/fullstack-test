<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/{province?}', function ($province = null) {
    return Inertia::render('Welcome', [
        'province' => $province,
    ]);
})->where('province', '[a-zA-Z]+');

require __DIR__.'/auth.php';
