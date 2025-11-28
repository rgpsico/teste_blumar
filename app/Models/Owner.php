<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'document',
        'address',
        'city',
        'state',
        'zip_code',
    ];

    /**
     * Relacionamento com propriedades (se necessário no futuro)
     */
    public function properties()
    {
        return $this->hasMany(Property::class);
    }
}
