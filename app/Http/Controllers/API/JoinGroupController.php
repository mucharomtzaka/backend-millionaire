<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\JoinGroupResource;
use App\Models\Group;
use App\Models\JoinGroup;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JoinGroupController extends Controller
{
    public function index($id)
    {
        try {

            $data = JoinGroup::where('group_id', $id)->get();

            $result = JoinGroupResource::collection($data);

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
        $nameGroup = $request->name ;
        $password = $request->password;

        //query Group is Exist?
        $query = Group::where([
                        'name' => $nameGroup,
                        'password' => $password,
                        'isOpen' => 1
                    ])->first();
        if($query) {
            $group = JoinGroup::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'group_id' => $query->id
                ],
                [
                    'isAllow' => 1
                ]
            );
            return $this->sendResponse(new JoinGroupResource($group), 'Success');
        }
        else {
            return $this->sendError('Sorry, Group '.$nameGroup. ' No Available', 404);
        }


    }

    public function update(Request $request, $id)
    {
        $joingroup = JoinGroup::find($id);

        $joingroup->isAllow = $request->status;

        $joingroup->update();

        return $this->sendResponse($joingroup, 'Success');
    }
}
