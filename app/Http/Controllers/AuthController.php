<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
    * @OA\Post(
    *     path="/api/login",
    *     summary="Atutenticar usuário",
    *     tags={"Autenticação"},
    *     @OA\Parameter(
    *         name="email",
    *         in="query",
    *         description="User's email",
    *         required=true,
    *         example="test@example.com",
    *         @OA\Schema(type="string")
    *     ),
    *     @OA\Parameter(
    *         name="password",
    *         in="query",
    *         description="User's password",
    *         required=true,
    *         example="123456789a",
    *         @OA\Schema(type="string")
    *     ),
    *     @OA\Response(response="200", description="Login successful"),
    *     @OA\Response(response="401", description="Invalid credentials")
    * )
    */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Credenciais inválidas'], 401);
        }

        // Cria token válido por 5 minutos
        $token = $user->createToken('auth-token', ['*'], now()->addMinutes(5))->plainTextToken;

        return response()->json(['token' => $token]);
    }

    /**
    * @OA\Post(
    *     path="/api/logout",
    *     summary="Logout",
    *     tags={"Autenticação"},
    *
    *     @OA\Response(response="200", description="Login successful"),
    *     @OA\Response(response="401", description="Invalid credentials"),
    *     security={{"bearerAuth":{}}}
    * )
    */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    /**
    * @OA\Post(
    *     path="/api/refresh",
    *     summary="Renovar Token",
    *     tags={"Autenticação"},
    *     @OA\Response(response="200", description="Login successful"),
    *     @OA\Response(response="401", description="Invalid credentials"),
    * )
    */
    public function refresh(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete(); // Revoga tokens antigos

        $newToken = $user->createToken('auth-token', ['*'], now()->addMinutes(5))->plainTextToken;

        return response()->json(['token' => $newToken]);
    }
}
