<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Param extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'filter_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function childs()
    {
        return $this->hasMany(Param::class, 'id', 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Param::class, 'parent_id');
    }
}
