<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeachHouse extends Model
{
    protected $table = 'beach_houses';
    protected $primaryKey = 'pk_beach';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'cor',
        'cidade',
        'nome',
        'descritivo',
        'foto1',
        'foto2',
        'foto3',
        'ativo'
    ];
}
