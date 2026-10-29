<?php

use App\Http\Controllers\Api\V1\QuizController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('questions', [QuizController::class, 'index']);
Route::post('quiz/submit', [QuizController::class, 'submit']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
