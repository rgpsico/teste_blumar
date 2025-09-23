<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImovelFoto extends Model
{
    use HasFactory;

    protected $table = 'imoveis_fotos';

    protected $fillable = [
        'imovel_id',
        'path',
    ];

    // Relação inversa para o imóvel
    public function imovel()
    {
        return $this->belongsTo(Imovel::class);
    }
}
