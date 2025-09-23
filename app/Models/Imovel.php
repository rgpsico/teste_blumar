<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imovel extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titulo',
        'descricao',
        'preco',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function endereco()
    {
        return $this->hasOne(ImovelEndereco::class);
    }

    public function fotos()
    {
        return $this->hasMany(ImovelFoto::class);
    }
}
