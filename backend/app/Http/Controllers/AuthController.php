<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $datos = $request->validated();

        $user = User::where('email', $datos['email'])->first();

        if (!$user || !Hash::check($datos['password'], $user->password)) {
            return response()->json([
                'message' => 'Credenciales inválidas',
            ], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $datos = $request->validated();

        $user = User::create([
            'name'     => $datos['name'],
            'email'    => $datos['email'],
            'password' => Hash::make($datos['password']),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ], 201);
    }

    public function logout()
    {
        $user = auth()->user();
        if ($user) {
            $user->tokens->each(function ($token) {
                $token->delete();
            });
        }

        return response()->json([
            'message' => 'Cierre de sesión exitoso',
        ]);
    }
}
