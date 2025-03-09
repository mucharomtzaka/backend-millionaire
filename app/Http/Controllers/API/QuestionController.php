<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AnswerResource;
use App\Http\Resources\QuestionResource;
use App\Models\Option;
use App\Models\Question;
use Exception;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function show($id)
    {
        try {

            $data = Question::where('quiz_id', $id)->orderBy('no', 'asc')->get();

            $result = QuestionResource::collection($data);

            return $this->sendResponse($result, 'Success');

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

    public function getAnswer($id)
    {
        $data = Option::where('questions_id', $id)->get();

        $result = AnswerResource::collection($data);

        return $this->sendResponse($result, 'Success');
    }

    public function trueAnswer($id)
    {
        $data = Question::select('answer')->find($id);

        return $this->sendResponse($data->answer, 'Success');
    }
}
