<?php

use App\Http\Controllers\Api\V1\QuizController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('quiz/attempts/{quizAttempt}', [QuizController::class, 'show']);
Route::post('quiz/attempts', [QuizController::class, 'store']);
Route::post('quiz/attempts/{id}/submit', [QuizController::class, 'submit']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
