<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Resources\AuthResource;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function __construct(private UserRepositoryInterface $userRepository) {}
    
    function register(AuthRequest $authRequest) {
        $validated = $authRequest->validated();

        $user = $this->userRepository->create($validated);

        $token = $user->createToken('api-token', ['post:read', 'post:create'])->plainTextToken;

        return AuthResource::make($user)->withToken($token);
    }

    function login(Request $request) {
        $validated = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|min:6',
        ]);

        if (!Auth::attempt($validated)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = User::where('email', $validated['email'])->firstOrFail();
        $token = $user->createToken('api-token', ['post:read', 'post:create'])->plainTextToken;

        return response()->json([
            'ok' => true,
            'user' => $user,
            'token' => $token
        ], 200);
    }

    function logout(Request $request) {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'message' => 'token not found'
            ], 400);
        }
        
        $access_token = PersonalAccessToken::findToken($token);

        if (!$access_token) {
            return response()->json([
                'message' => 'invalid token'
            ], 400);
        }

        $access_token->delete();

        return response()->json([
            'message' => 'Logged out'
        ], 200);
    }
}
