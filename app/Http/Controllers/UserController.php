<?php

namespace App\Http\Controllers;

use App\Constants\ErrorMessages;
use App\Constants\SuccessMessages;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $users = User::all();

            return response()->json([
                'message' => __(SuccessMessages::USER_RETRIEVED),
                'data' => $users,
            ], 200);
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
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|string|email|max:100|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            return response()->json([
                'message' => __(SuccessMessages::USER_CREATED),
                'data' => $user,
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

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            return response()->json([
                'message' => __(SuccessMessages::USER_FOUND),
                'data' => $user,
            ], 200);
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

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:100',
                'email' => 'sometimes|required|string|email|max:100|unique:users,email,'.$id,
                'password' => 'sometimes|required|string|min:8|confirmed',
            ]);

            if (isset($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            }

            $user->update($validated);

            return response()->json([
                'message' => __(SuccessMessages::USER_UPDATED),
                'data' => $user->fresh(),
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => __(ErrorMessages::VALIDATION_FAILED),
                'details' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => __(ErrorMessages::USER_UPDATE_FAILED),
            ], 500);
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

            return response()->json([
                'message' => __(SuccessMessages::USER_DELETED),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => __(ErrorMessages::USER_DELETION_FAILED),
            ], 404);
        }
    }
}
