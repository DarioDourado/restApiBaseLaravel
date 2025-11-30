<?php

namespace App\Http\Controllers;

use App\Constants\ErrorMessages;
use App\Constants\SuccessMessages;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            // optional: give abilities, e.g. ['users:read']
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'message' => __(SuccessMessages::USER_CREATED),
                'data' => $user,
                'token' => $token,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => __(ErrorMessages::VALIDATION_FAILED),
                'details' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => __(ErrorMessages::USER_CREATION_FAILED),
            ], 500);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $user = User::where('email', $data['email'])->first();

            if (! $user || ! Hash::check($data['password'], $user->password)) {
                return response()->json([
                    'error' => __(ErrorMessages::UNAUTHORIZED),
                ], 401);
            }

            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'message' => __('Login successful'),
                'data' => $user,
                'token' => $token,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => __(ErrorMessages::SERVER_ERROR),
            ], 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $token = $request->user()?->currentAccessToken();

            if ($token) {
                $token->delete();
                return response()->json([
                    'message' => __('Logged out'),
                ], 200);
            }

            // se não houver token
            return response()->json([
                'error' => __(ErrorMessages::UNAUTHORIZED),
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'error' => __(ErrorMessages::SERVER_ERROR),
            ], 500);
        }
    }
}
