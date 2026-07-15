<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('/admin', function () {
    return 'Central Admin Dashboard';
});
