<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyFaq extends Model
{
    protected $fillable = ['property_id', 'question', 'answer'];
}
