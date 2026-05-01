<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'tbl_productmaster';
    protected $fillable = ['productname', 'category', 'amt', 'gst', 'description', 'hardware_details', 'software_details'];

    protected static function boot()
    {
        parent::boot();

        // This runs automatically before saving a new record
        static::creating(function ($product) {
            $product->softwareprice = 0;
            $product->hardwareprice = 0;
        });
    }
}