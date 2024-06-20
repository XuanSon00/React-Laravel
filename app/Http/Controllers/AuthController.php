<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function user(Request $request)
    {
        return $request->user();
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'string|nullable',
            'active' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? '',
            'active' => $request->active ?? '',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Đăng ký người dùng thành công',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $user  = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'role' => $user->role,
        ]);
    }

    public function logout(Request $request)
    {
        dd($request);
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function getAllUsers()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function show($id)
    {
        $subject = User::find($id);
        if (!$subject) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($subject, 200);
    }

    public function update(Request $request, $id)
    {
        $subject = User::find($id);
        if (!$subject) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $subject->update($request->all());
        return response()->json($subject, 200);
    }

    public function destroy($id)
    {
        $subject = User::find($id);
        if (!$subject) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $subject->delete();
        return response()->json(['message' => 'User deleted'], 200);
    }

    public function destroyAll()
    {
        User::truncate();
        return response()->json(['message' => 'User deleted'], 200);
    }
}
