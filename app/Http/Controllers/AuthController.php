<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // LOGIN
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

    // LOGOUT (revoga token)
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    // RENOVAÇÃO DE TOKEN
    public function refresh(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete(); // Revoga tokens antigos

        $newToken = $user->createToken('auth-token', ['*'], now()->addMinutes(5))->plainTextToken;

        return response()->json(['token' => $newToken]);
    }
}
