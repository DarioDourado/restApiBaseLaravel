<?php

namespace App\Http\Controllers;

use App\Constants\ErrorMessages;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $users = User::all();

            return response()->json($users, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => __(ErrorMessages::USER_RETRIEVAL_FAILED),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json(['message' => 'Not implemented'], 501);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => __(ErrorMessages::USER_NOT_FOUND),
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => __(ErrorMessages::USER_NOT_FOUND),
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'error' => __(ErrorMessages::USER_NOT_FOUND),
            ], 404);
        }
    }
}
