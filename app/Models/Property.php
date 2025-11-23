<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = [
        'owner_id',
        'community_id',
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
        'video_url',
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

    public function faqs()
    {
        return $this->hasMany(\App\Models\PropertyFaq::class);
    }
    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function leases()
    {
        return $this->hasMany(Tenant::class);
    }
}
