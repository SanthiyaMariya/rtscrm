<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMaster extends Model
{
    protected $table = 'tbl_productmaster';
    public $timestamps = false; // Because your table uses custom updated_at and created_at formats

    protected $fillable = [
        'productname', 'category', 'hardwareprice', 'hardware_details',
        'softwareprice', 'software_details', 'description', 'amt', 'gst',
        'hardware_descp', 'software_descp', 'created_at', 'updated_at',
        'created_ip', 'updated_ip'
    ];
}