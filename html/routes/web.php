<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/{province?}', function ($province = null) {
    return Inertia::render('Welcome', [
        'province' => $province,
    ]);
})->where('province', '[a-zA-Z]+')->name('home-page');

Route::get('/404', function () {
    return Inertia::render('404');
});

require __DIR__.'/auth.php';
