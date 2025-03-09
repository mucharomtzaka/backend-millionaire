<?php

use App\Events\UserListEvent;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\GroupController;
use App\Http\Controllers\API\JoinGroupController;
use App\Http\Controllers\API\PuzzleController;
use App\Http\Controllers\API\QuestionBattleController;
use App\Http\Controllers\API\QuestionController;
use App\Http\Controllers\API\QuizController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json(['user' => $request->user()]);
});

Route::middleware('auth:sanctum')->group(function () {
    //get Profile
    Route::get('user', [UserController::class, 'show']);

    Route::get('logout', [AuthController::class, 'logout']);

    Route::get('quiz', [QuizController::class, 'index']);

    Route::get('questions/{id}', [QuestionController::class, 'show']);

    Route::get('question-answer/{id}', [QuestionController::class, 'trueAnswer']);

    Route::get('answer/{id}', [QuestionController::class, 'getAnswer']);

    //submit Respond
    Route::post('user-submit', [QuizController::class, 'userSubmit']);
    Route::post('updateResponse', [QuizController::class, 'updateResponse']);


    //PUZZLE QUIZ
    //group
    Route::get('group', [GroupController::class, 'index']);
    Route::post('group', [GroupController::class, 'store']);
    Route::put('group/{id}', [GroupController::class, 'offGroup']);

    //list join group for admin
    Route::get('listjoin', [JoinGroupController::class, 'index']); // list join

    //update
    Route::put('joinupdate', [JoinGroupController::class, 'update']);
    //delete
    Route::delete('joindelete', [JoinGroupController::class, 'delete']);

    //quiz puzzle or battle
    Route::get('battle', [PuzzleController::class, 'index']);

    //get question battle
    Route::get('question-battle/{id}', [QuestionBattleController::class, 'show']);

    //get question answer
    Route::get('answer-battle/{id}', [QuestionBattleController::class, 'getAnswer']);

    //realtime
    //send question to group
    Route::post('showQuestion', [QuestionBattleController::class, 'showQuestion']);

    Route::get('broadcast',[AuthController::class,'broadcast']);

});

Route::get('showQuestion', [QuestionBattleController::class, 'showQuestion']);
Route::get('test', function (){
     event(new UserListEvent('halooo'));
     return 'Ok';
});

Route::post('login', [AuthController::class, 'login']);
