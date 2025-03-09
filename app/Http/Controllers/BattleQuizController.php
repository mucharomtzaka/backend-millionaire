<?php

namespace App\Http\Controllers;

use App\Models\JoinGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BattleQuizController extends Controller
{
    public function index()
    {
        //check user when Join Group
        $data = JoinGroup::where('user_id',Auth::id())
                            ->whereHas('group', function ($query) {
                                            $query->where('isOpen', 1);
                                        })->first();

        return view('battle.index_battle', compact('data'));
    }


}
