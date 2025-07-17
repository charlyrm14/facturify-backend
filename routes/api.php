<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AuthController
};

Route::prefix('/')->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::get('user', 'getUserAuthenticated')->middleware('jwt.verify');
    });

});
