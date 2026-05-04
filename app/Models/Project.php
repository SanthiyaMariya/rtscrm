<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'tbl_projectmaster'; // Your actual table name
    public $timestamps = false; // Based on your SQL dump, this table doesn't have standard timestamps

    protected $fillable = [
        'projectname', 
        'clientname', 
        'clientcontactno', 
        'platform', 
        'description', 
        'language'
    ];
}