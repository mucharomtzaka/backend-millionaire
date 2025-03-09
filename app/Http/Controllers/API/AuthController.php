<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Events\AuthloginEvent;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return $this->sendError('Unauthorized', 'Authentication Failed', 500);
            }

            // Ensure authenticated user is correctly set
             $user = Auth::user();

            // Generate token
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return $this->sendResponse([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authenticated');

        } catch (Exception $error) {
            return $this->sendError(
                [
                    'message' => 'Something went wrong',
                    'error' => $error
                ],
                'Login Failed',
            );
        }
    }

    public function broadcast(){
       $user = Auth::user();
      // Ambil daftar pengguna yang sudah login dari cache
      $userList = Cache::get('userList', []);

      // Tambahkan user ke daftar jika belum ada
      if (!in_array($user->id, array_column($userList, 'id'))) {
          $userList[] = [
              'id' => $user->id,
              'name' => $user->name,
              'email' => $user->email,
          ];
      }

      // Simpan kembali ke cache
      Cache::put('userList', $userList, now()->addMinutes(60));

      // Broadcast event ke frontend
       event(new AuthloginEvent($userList));
       return $this->sendResponse($user,'log');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:100'],
                'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
                'password' => ['required', 'min:6']
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user = User::where('email', $request->email)->first();
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            $data = [
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ];
            return $this->sendResponse($data, 'Successfull Register');
        } catch (Exception $error) {
            return $this->sendError(
                [
                    'message' => 'Something went wrong',
                    'error' => $error
                ],
                'Registration Failed',
            );
        }
    }

    public function logout(Request $request)
    {
        $user = User::find(Auth::user()->id);

        $user->tokens()->delete();
        return response()->noContent();
    }


}
