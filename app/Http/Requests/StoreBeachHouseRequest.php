<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBeachHouseRequest extends FormRequest
{
    public function authorize()

    {

        return true; // Pode ajustar conforme regras de autenticação
    }

    public function rules(): array
    {
        return [
            'cor' => 'required|string|max:50',
            'cidade' => 'required|string|max:100',
            'nome' => 'required|string|max:150',
            'descritivo' => 'required|string',
            'foto1' => 'nullable|string|max:255',
            'foto2' => 'nullable|string|max:255',
            'foto3' => 'nullable|string|max:255',
            'ativo' => 'boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'cor.required' => 'O campo cor é obrigatório.',
            'cor.string' => 'O campo cor deve ser um texto.',
            'cor.max' => 'O campo cor não pode ter mais de 50 caracteres.',

            'cidade.required' => 'O campo cidade é obrigatório.',
            'cidade.string' => 'O campo cidade deve ser um texto.',
            'cidade.max' => 'O campo cidade não pode ter mais de 100 caracteres.',

            'nome.required' => 'O campo nome é obrigatório.',
            'nome.string' => 'O campo nome deve ser um texto.',
            'nome.max' => 'O campo nome não pode ter mais de 150 caracteres.',

            'descritivo.required' => 'O campo descritivo é obrigatório.',
            'descritivo.string' => 'O campo descritivo deve ser um texto.',

            'foto1.string' => 'O campo foto1 deve ser um texto.',
            'foto1.max' => 'O campo foto1 não pode ter mais de 255 caracteres.',

            'foto2.string' => 'O campo foto2 deve ser um texto.',
            'foto2.max' => 'O campo foto2 não pode ter mais de 255 caracteres.',

            'foto3.string' => 'O campo foto3 deve ser um texto.',
            'foto3.max' => 'O campo foto3 não pode ter mais de 255 caracteres.',

            'ativo.boolean' => 'O campo ativo deve ser verdadeiro ou falso.'
        ];
    }
}
