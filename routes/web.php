<?php

use Illuminate\Support\Facades\Route;

Route::post('/', function () {
    return ['Laravel' => app()->version()];
});

//require __DIR__.'/auth.php';
