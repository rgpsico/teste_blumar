<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cep',
        'rua',
        'numero',
        'bairro',
        'cidade',
        'estado',
        'pais',
        'complemento',
    ];

    // Relação inversa para o usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
