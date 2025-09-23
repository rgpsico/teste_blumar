<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CepController extends Controller
{
    public function show($cep)
    {
        // Limpa o CEP, removendo caracteres não numéricos
        $cep = preg_replace('/[^0-9]/', '', $cep);

        // Verifica se o CEP tem 8 dígitos
        if (strlen($cep) !== 8) {
            return response()->json(['erro' => 'CEP inválido.'], 400);
        }

        // Faz a requisição para a API do ViaCEP
        $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");

        // Retorna a resposta da API do ViaCEP
        return response()->json($response->json());
    }
}
