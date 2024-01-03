<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductContent extends Model
{
    use HasFactory;

    protected $table = 'product_content';

    protected $fillable = [
        'image',
        'vendor',
        'description',
        'vendorCode',
        'available',
    ];

    protected $primaryKey = 'product_id';

    public $timestamps = false;

    protected $casts = [
        'available' => 'boolean',
    ];

    public function product()
    {
        return $this->hasOne(Product::class);
    }
}
