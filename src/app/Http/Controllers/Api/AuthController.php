<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Authenticate user and return token.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required_without:employee_number|email',
            'employee_number' => 'required_without:email|string',
            'password' => 'required|string',
        ]);

        $user = null;

        if ($request->has('email')) {
            $user = User::where('email', $request->email)->first();
        } elseif ($request->has('employee_number')) {
            $user = User::where('employee_number', $request->employee_number)->first();
        }

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user->load('role'),
        ]);
    }

    /**
     * Revoke user's current token.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
