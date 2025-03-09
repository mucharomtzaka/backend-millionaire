<?php

use App\Http\Controllers\API\JoinGroupController;
use App\Http\Controllers\API\PuzzleController;
use App\Http\Controllers\BattleQuizController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/battle', [BattleQuizController::class, 'index'])->name('battle.index');

     //user join group
     Route::post('/joinstore', [JoinGroupController::class, 'store']);
     //user submit answer
     Route::post('battleSubmit', [PuzzleController::class, 'userSubmit']);
});

require __DIR__.'/auth.php';

