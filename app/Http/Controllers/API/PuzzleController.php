<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PuzzleResource;
use App\Models\Puzzle;
use App\Models\PuzzleQuestion;
use App\Models\ResponsePuzzle;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PuzzleController extends Controller
{
    public function index()
    {
        try {

            $data = Puzzle::paginate(20);

            $result = PuzzleResource::collection($data)->resource;

            return $this->sendResponse($result, 'Successfull get user');

        } catch (Exception $error) {
            return $this->sendError(
                [
                    'message' => 'Something went wrong',
                    'error' => $error->getMessage()
                ],
                'Error got it.',
            );
        }
    }

    public function userSubmit(Request $request)
    {

        //check the answer
        $query = PuzzleQuestion::select('word', 'point')->find($request->question_id);
        $userAnswer = strtoupper($request->answer);
        $isCorrect = false;
        if($query){
            $query->word == $userAnswer ? $isCorrect = true : $isCorrect = false;
        }
        // save to Detail Respond
        $resp = ResponsePuzzle::create([
            'answer' => $userAnswer,
            'isCorrect' => $isCorrect,
            'point' => $isCorrect ? $query->point : 0,
            'puzzle_question_id' => $request->question_id,
            'puzzle_id' => $request->puzzle_id,
            'user_id' => Auth::id()
        ]);

        return $this->sendResponse($resp, 'Success');
    }
}
