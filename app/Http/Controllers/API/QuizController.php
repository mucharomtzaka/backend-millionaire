<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuizResource;
use App\Models\DetailResponse;
use App\Models\Option;
use App\Models\Quiz;
use App\Models\Response;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        try {

            $data = Quiz::paginate(20);

            $result = QuizResource::collection($data)->resource;

            return $this->sendResponse($result, 'Successfull get user');

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

    public function userSubmit(Request $request)
    {
        // save to Detail Respond
        $resp = Response::create([
            'name' => $request->name,
            'nominal_challenge' => $request->nominal,
            'user_id' => Auth::id(),
            'quiz_id' => $request->idQuiz
        ]);

        return $this->sendResponse($resp, 'Success');
    }

    public function updateResponse(Request $request)
    {
        // save to Detail Respond
        $resp = Response::where('id', $request->idResponse)
        ->update([
            'nominal_get' => $request->nominal
        ]);

        return $this->sendResponse($resp, 'Success');
    }

}
