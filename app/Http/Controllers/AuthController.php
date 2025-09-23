<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Cadastro de usuário
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'cep' => 'required|string',
            'rua' => 'required|string',
            'numero' => 'required|string',
            'bairro' => 'nullable|string',
            'cidade' => 'nullable|string',
            'estado' => 'nullable|string',
            'pais' => 'nullable|string',
            'complemento' => 'nullable|string',
        ]);

        // Criar usuário
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Criar endereço
        UserAddress::create([
            'user_id' => $user->id,
            'cep' => $request->cep,
            'rua' => $request->rua,
            'numero' => $request->numero,
            'bairro' => $request->bairro,
            'cidade' => $request->cidade,
            'estado' => $request->estado,
            'pais' => $request->pais,
            'complemento' => $request->complemento,
        ]);

        return response()->json(['message' => 'Usuário registrado com sucesso'], 201);
    }

    // Login de usuário
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Credenciais inválidas.'],
            ]);
        }

        $user = Auth::user();

        // Retornar user info (ou token se usar API)
        return response()->json([
            'user' => $user,
            'message' => 'Login realizado com sucesso',
        ]);
    }

    // Logout (opcional)
    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Logout realizado com sucesso']);
    }
}
