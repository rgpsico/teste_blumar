<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateImovelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Pode colocar lógica de permissão se necessário
    }

    public function rules(): array
    {
        return [
            'titulo' => 'sometimes|required|string|max:255',
            'descricao' => 'sometimes|required|string',
            'preco' => 'sometimes|required|numeric',
            'pais' => 'sometimes|required|string',
            'cep' => 'sometimes|required|string',
            'rua' => 'sometimes|required|string',
            'numero' => 'sometimes|required|string',
            'bairro' => 'nullable|string',
            'cidade' => 'nullable|string',
            'complemento' => 'nullable|string',
            'fotos.*' => 'nullable|image|max:2048',
        ];
    }
}
