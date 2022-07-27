<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use Validator;

//models
use App\Models\User;

class AuthController extends Controller
{
    public function registerAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|required|max:255',
            'email' => 'email|required|unique:users,email',
            'password' => 'string|required|max:255',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $key => $value) {
                $errors[] = $value;
            }

            return response()->json([
               'statusCode' => 400,
               'message' => 'Bad request',
               'data' => $errors 
            ], 400);
        }

        try {
            $saveData = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'Admin',
                'verified' => 1
            ]);

            return response()->json([
                'statusCode' => 201,
                'message' => 'create account successfully',
                'data' => $saveData
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Internal Error',
                'data' => $th
            ], 500);
        }
    }

    public function registerUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|required|max:255',
            'email' => 'email|required|unique:users,email',
            'password' => 'string|required|max:255',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors() as $key => $value) {
                $errors[] = $value;
            }

            return response()->json([
               'statusCode' => 400,
               'message' => 'Bad request',
               'data' => $errors 
            ], 400);
        }

        try {
            $saveData = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'User'
            ]);

            return response()->json([
                'statusCode' => 201,
                'message' => 'create account successfully',
                'data' => $saveData
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'statusCode' => 500,
                'message' => 'Internal Error',
                'data' => $th
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'email|required',
            'password' => 'string|required',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors() as $key => $value) {
                $errors[] = $value;
            }

            return response()->json([
               'statusCode' => 400,
               'message' => 'Bad request',
               'data' => $errors 
            ], 400);
        }

        $checkData = User::where('email',$request->email)->first();
        if (!$checkData) {
            return response()->json([
                'statusCode' => 404,
                'message' => 'Unregistered account',
            ], 404);
        }

        $validate = $request->all();
        if (Auth::attempt($validate)) {
            $token = $checkData->createToken('token-name')->plainTextToken;

            $checkData['accessToken'] = $token;
            $checkData['tokenType'] = 'Bearer';

            return response()->json([
                'statusCode' => 200,
                'message' => 'login successfully',
                'data' => $checkData
            ], 200);
        } else {
            return response()->json([
                'statusCode' => 4001,
                'message' => 'Wrong password',
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
    }

    public function notAuthenticated(Type $var = null)
    {
        return response()->json([
            'statusCode' => 401,
            'message' => 'Unauthorized',
        ], 401);
    }
}
