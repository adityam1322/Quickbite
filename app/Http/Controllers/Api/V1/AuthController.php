<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterRequest $request){
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,

        ]);

        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json([
            'massage' => 'registration successful',
            'user' => $user,
            'token' => $token,
        ],201);


        public function logout(Request $request){
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Logout successful',
            ]);
        }

        public function me(Request $request){
            return response()->json([
                'user' => $request->user(),
            ]);
        }
    }
}
