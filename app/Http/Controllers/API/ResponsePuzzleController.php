<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResponsePuzzleResource;
use App\Models\JoinGroup;
use App\Models\PuzzleQuestion;
use App\Models\ResponsePuzzle;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResponsePuzzleController extends Controller
{
    public function index(Request $request)
    {
        try {

            $query = ResponsePuzzle::query();

            if($request->puzzle)
            {
                $query->where('puzzle_id', $request->puzzle);
            }

            if($request->question)
            {
                $query->where('puzzle_question_id', $request->question);
            }

            $data = $query->get();

            $result = ResponsePuzzleResource::collection($data);

            return $this->sendResponse($result, 'Successfull get joinGroup');

        } catch (Exception $error) {
            return $this->sendError(
                [
                    'message' => 'Something went wrong',
                    'error' => $error
                ],
                'Authentication Failed',
            );
        }
    }

    public function store(Request $request)
    {
        //check user can answer
        if($this->isAllowUser($request->idGroup))
        {
        //check the answer
        $dataAnswer = PuzzleQuestion::select('answer')->where('puzzle_question_id', $request->questionId)->first();

        $dataAnswer->answer == $request->answer ? $isCorrect = true : $isCorrect = false ;

        $response = ResponsePuzzle::create([
            'answer' => $request->answer,
            'isCorrect' => $isCorrect,
            'point' => $isCorrect ? $request->point : 0,
            'puzzle_question_id' => $request->questionId,
            'puzzle_id' => $request->puzzleId,
            'user_id' => Auth::id(),
        ]);

        return $this->sendResponse($response, 'Success');
        }
        else {
            return $this->sendError(null, 'Now Allowed', 403);
        }


    }

    public function update(Request $request, $id)
    {
        $data = ResponsePuzzle::find($id);

        $data->answer = $request->answer;
        $data->isCorrect = $request->isCorrect;
        $data->point = $request->point;
        $data->puzzle_question_id = $request->questionId;
        $data->puzzle_id = $request->puzzleId;

        $data->update();

        return $this->sendResponse($data, 'Success');
    }

    public function delete($id)
    {
        $data = ResponsePuzzle::find($id);

        if($data) $data->delete();

        return $this->sendResponse(null, 'Success');
    }

    public function isAllowUser($idGroup)
    {
        $idUser = Auth::user()->id;

        $row = JoinGroup::where('user_id', $idUser)->where('group_id', $idGroup)->where('isAllow', 1)->count();

        if($row)
        {
            return true;
        }
        else {
            return false;
        }

    }
}
