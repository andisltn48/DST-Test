<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ManajemenUserController extends Controller
{
    public function getAllUser(Request $request)
    {
        $user = User::where('role','User')->get();

        if ($user) {
            return response()->json([
                'statusCode' => 200,
                'data' => $user
            ], 200);
        } else {
            
            return response()->json([
                'statusCode' => 404,
                'message' => 'user not found',
                'data' => null
            ], 404);
        }
    }

    public function verifyUser(Request $request, $userId)
    {
        $user = User::find($userId);

        if ($user) {
            $user->update([
                'verified' => 1
            ]);

            return response()->json([
                'statusCode' => 201,
                'data' => $user
            ], 201);
        } else {
            
            return response()->json([
                'statusCode' => 404,
                'message' => 'user not found',
                'data' => null
            ], 404);
        }
    }
}
