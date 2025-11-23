<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = [
        'owner_id',
        'tenant_id',
        'title',
        'description',
        'address',
        'city',
        'state',
        'zip_code',
        'price',
        'bedrooms',
        'bathrooms',
        'area',
        'photos',
        'status',
        'active',
    ];

    protected $casts = [
        'photos' => 'array',
        'price' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function leases()
    {
        return $this->hasMany(Tenant::class);
    }
}
