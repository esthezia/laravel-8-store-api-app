<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'id_category',
        'name',
        'sku',
        'price',
        'quantity',
        'created_at'
    ];

    protected $hidden = [
        'created_by'
    ];

    protected $casts = [
        'price' => 'float',
        'quantity' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    // 'updated_at' should only be set on 'update'
    protected static function booted(): void {
        static::creating(function (self $model) {
            $model->updated_at = null;
        });
    }

    public function category () {
        return $this->belongsTo(Category::class, 'id_category', 'id');
    }
}
