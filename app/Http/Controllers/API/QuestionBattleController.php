<?php

namespace App\Http\Controllers\API;

use App\Events\AnswerSent;
use App\Events\GroupMessageSent;
use App\Http\Controllers\Controller;
use App\Http\Resources\PuzzleQuestionResource;
use App\Models\Group;
use App\Models\PuzzleQuestion;
use Exception;
use Illuminate\Http\Request;

class QuestionBattleController extends Controller
{
    public function show($id)
    {
        try {

            $data = PuzzleQuestion::where('puzzle_id', $id)->orderBy('no', 'asc')->get();

            $result = PuzzleQuestionResource::collection($data);

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
        $data = PuzzleQuestion::select('word')->where('id', $id)->first();

        $group = Group::where('isOpen', 1)->first();

        broadcast(new AnswerSent($group->id, $data))->toOthers();

        return $this->sendResponse($data->word, 'Success');
    }

    //show quiz for group -> realtime
    public function showQuestion(Request $request)
    {
        //get Data
        $data = $request->all();

        //get Group which open
        $group = Group::where('isOpen', 1)->first();

        broadcast(new GroupMessageSent($group->id, $data))->toOthers();

        return response()->json(['status' => 'Message sent!']);
    }


}
