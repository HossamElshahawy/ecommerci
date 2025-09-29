<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/website')->group(function () {
    Route::resource('users', UserController::class);
});
