<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreImovelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Pode colocar lógica de permissão se necessário
    }

    public function rules(): array
    {
        return [
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'preco' => 'required|numeric',
            'pais' => 'required|string',
            'cep' => 'required|string',
            'rua' => 'required|string',
            'numero' => 'required|string',
            'bairro' => 'nullable|string',
            'cidade' => 'nullable|string',
            'complemento' => 'nullable|string',
            'fotos.*' => 'nullable|image|max:2048', // múltiplas fotos
        ];
    }
}
