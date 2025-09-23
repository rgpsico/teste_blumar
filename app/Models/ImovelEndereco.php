<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImovelEndereco extends Model
{
    use HasFactory;

    protected $fillable = [
        'imovel_id',
        'pais',
        'cep',
        'rua',
        'numero',
        'bairro',
        'cidade',
        'complemento',
    ];

    // Relação inversa para o imóvel
    public function imovel()
    {
        return $this->belongsTo(Imovel::class);
    }
}
