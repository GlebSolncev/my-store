<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Characteristic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function params()
    {
        return $this->hasMany(Param::class);
    }

    public function parent()
    {
        return $this->belongsTo(Characteristic::class, 'parent_id');
    }
}
