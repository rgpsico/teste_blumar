<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

/**
 * @OA\Info(
 *     title="Beach API",
 *     version="1.0.0"
 * )
 *
 * @OA\Tag(
 *     name="Auth",
 *     description="Autenticação de usuários"
 * )
 */
class AuthController extends Controller
{

    /**
     * @OA\Get(
     *     path="/login",
     *     tags={"Auth"},
     *     summary="Exibe o formulário de login",
     *     @OA\Response(
     *         response=200,
     *         description="Formulário de login"
     *     )
     * )
     */
    public function showLogin()
    {
        return Inertia::render('Auth/Login');
    }




    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Auth"},
     *     summary="Realiza o login do usuário",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Login realizado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Credenciais inválidas"
     *     )
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success', 'Login realizado com sucesso!');
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ]);
    }


    /**
     * @OA\Get(
     *     path="/register",
     *     tags={"Auth"},
     *     summary="Exibe o formulário de cadastro",
     *     @OA\Response(
     *         response=200,
     *         description="Formulário de cadastro"
     *     )
     * )
     */
    public function showRegister()
    {
        return Inertia::render('Auth/Register');
    }


    /**
     * @OA\Post(
     *     path="/register",
     *     tags={"Auth"},
     *     summary="Realiza o cadastro do usuário",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password"),
     *             @OA\Property(property="password_confirmation", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Cadastro realizado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados inválidos"
     *     )
     * )
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Cadastro realizado com sucesso!');
    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     tags={"Auth"},
     *     summary="Realiza o logout do usuário",
     *     @OA\Response(
     *         response=302,
     *         description="Logout realizado com sucesso"
     *     )
     * )
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logout realizado com sucesso!');
    }
}
