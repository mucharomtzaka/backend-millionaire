<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index()
    {
        try {

            $data = Group::orderBy('id', 'desc')->paginate(20);

            $result = GroupResource::collection($data)->resource;

            return $this->sendResponse($result, 'Successfull get group');

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
        $group = Group::create([
            'name'=> $request->name,
            'password' => $request->password,
            'isOpen' => 1,
            'user_id' => Auth::id(),
        ]);

        return $this->sendResponse(new GroupResource($group), 'Success');
    }

    public function offGroup(Request $request, $id)
    {
        $group = Group::find($id);

        $group->isOpen = $request->status;
        $group->update();

        return $this->sendResponse($group, 'Success');
    }
}
