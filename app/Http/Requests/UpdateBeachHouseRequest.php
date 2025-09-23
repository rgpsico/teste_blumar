<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBeachHouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'nome' => 'required|string|max:100',
            'cor' => 'required|string|max:50',
            'cidade' => 'required|string|max:100',
            'descritivo' => 'nullable|string|max:255',
            'foto1' => 'nullable|url',
            'foto2' => 'nullable|url',
            'foto3' => 'nullable|url',
            'ativo' => 'boolean',
        ];
    }
}
