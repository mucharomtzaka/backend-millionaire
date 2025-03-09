<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QuizDuplicateController extends Controller
{
    public function duplicate($id)
    {
        DB::beginTransaction();

        try {
            // Get data quiz by ID
            $quiz = Quiz::findOrFail($id);

            // Duplicate data quiz
            $newQuiz = $quiz->replicate();
            $newQuiz->title = $quiz->title . ' Copy';
            $newQuiz->slug = Str::slug($quiz->title . ' Copy');
            $newQuiz->save();

            // Duplicate each question have relationship with quiz
            foreach ($quiz->questions as $question) {
                $newQuestion = $question->replicate();
                $newQuestion->quiz_id = $newQuiz->id;
                $newQuestion->save();
            }

            DB::commit();

            return true;

        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }
}
