<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AuthController,
    ConversationController,
    NotificationController
};

Route::prefix('/')->group(function () {

    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::get('user', 'getUserAuthenticated')->middleware('jwt.verify');
    });

    Route::prefix('threads/')->controller(ConversationController::class)->group(function () {
        Route::get('', 'index')->middleware('jwt.verify');
        Route::get('{thread_id}', 'threadDetail')->middleware('jwt.verify');
        Route::post('', 'createThread')->middleware('jwt.verify');
        Route::post('{thread_id}/messages', 'replyMessage')->middleware('jwt.verify');
    });

    Route::prefix('notifications/')->controller(NotificationController::class)->group(function () {
        Route::get('', 'index')->middleware('jwt.verify');
    });

});
