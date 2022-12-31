<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthenticatedTokenController extends Controller
{
    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(LoginRequest $request)
    {
        try {
            $request->authenticate();

            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('token-auth');

            return response()->json([
                'status' => true,
                'data' => [
                    'user' => $request->user(),
                    'token' => $token->plainTextToken,
                ]
            ], 200);
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Destroy an authenticated tokens.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            $request->user()->tokens()->delete();

            $response = [
                'status' => true,
                'code' => 201,
                'message' => 'Tokens Revoked',
            ];

            return response()->json($response, 201);
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}
