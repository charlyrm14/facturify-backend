<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AuthController,
    ConversationController
};

Route::prefix('/')->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::get('user', 'getUserAuthenticated')->middleware('jwt.verify');
    });

    Route::prefix('threads/')->controller(ConversationController::class)->group(function () {
        Route::get('', 'threadsList')->middleware('jwt.verify');
        Route::get('{thread_id}', 'threadDetail')->middleware('jwt.verify');
        Route::post('', 'createThread')->middleware('jwt.verify');
        Route::post('{thread_id}/messages', 'replyMessage')->middleware('jwt.verify');
    });

});
