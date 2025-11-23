<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class TokenController extends Controller
{
    /**
     * Generate API token for existing user
     * POST /api/token/create
     */
    public function create(Request $request)
    {
        Log::info('TokenController: Token creation attempt', [
            'email' => $request->email,
        ]);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'token_name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            Log::warning('TokenController: Invalid credentials', [
                'email' => $request->email,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        $tokenName = $request->token_name ?? 'api-token-' . now()->format('Y-m-d-H-i-s');
        $token = $user->createToken($tokenName)->plainTextToken;

        Log::info('TokenController: Token created successfully', [
            'user_id' => $user->id,
            'token_name' => $tokenName,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Token created successfully',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'access_token' => $token,
                'token_type' => 'Bearer',
                'token_name' => $tokenName,
            ],
        ], 201);
    }

    /**
     * Get all user's tokens
     * GET /api/tokens
     */
    public function index(Request $request)
    {
        $tokens = $request->user()->tokens;

        return response()->json([
            'success' => true,
            'data' => $tokens->map(function ($token) {
                return [
                    'id' => $token->id,
                    'name' => $token->name,
                    'abilities' => $token->abilities,
                    'last_used_at' => $token->last_used_at,
                    'created_at' => $token->created_at,
                ];
            }),
        ]);
    }

    /**
     * Revoke specific token
     * DELETE /api/tokens/{id}
     */
    public function destroy(Request $request, $tokenId)
    {
        $deleted = $request->user()->tokens()->where('id', $tokenId)->delete();

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found',
            ], 404);
        }

        Log::info('TokenController: Token revoked', [
            'user_id' => $request->user()->id,
            'token_id' => $tokenId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Token revoked successfully',
        ]);
    }

    /**
     * Revoke all tokens
     * DELETE /api/tokens
     */
    public function destroyAll(Request $request)
    {
        $request->user()->tokens()->delete();

        Log::info('TokenController: All tokens revoked', [
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'All tokens revoked successfully',
        ]);
    }
}