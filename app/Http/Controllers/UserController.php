<?php

namespace App\Http\Controllers;

use App\Constants\ErrorMessages;
use App\Constants\SuccessMessages;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

use App\Models\User;
use Illuminate\Http\JsonResponse;

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
    public function store(StoreUserRequest $request): JsonResponse
    {

        $data = $request->validated();

        try {

            $user = new User();
            $user->fill($data);
            $user->password = Hash::make($data['password']);
            $user->save();

            return response()->json([
                'message' => __(SuccessMessages::USER_CREATED),
                'data' => $user,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => __(ErrorMessages::VALIDATION_FAILED),
                'details' => $e->errors(),
            ], 400);
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
        if (!$id) return response()->json([
            'error' => __(e(ErrorMessages::USER_ID_NOT_FOUND)),
        ], 404);

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
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        if (!$id) return response()->json([
            'error' => __(e(ErrorMessages::USER_ID_NOT_FOUND)),
        ], 404);

        $data = $request->validated();

        try {

            $user = User::findOrFail($id);
            $user->save();

            return response()->json([
                'message' => __(SuccessMessages::USER_UPDATED),
                'data' => $user,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => __(ErrorMessages::VALIDATION_FAILED),
                'details' => $e->errors(),
            ], 400);
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
        if (!$id) return response()->json([
            'error' => __(e(ErrorMessages::USER_ID_NOT_FOUND)),
        ], 404);

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
