<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectMaster extends Model
{
    protected $table = 'tbl_projectmaster';
    public $timestamps = false; // Your table uses custom date fields

    protected $fillable = [
        'projectname', 'clientname', 'clientcontactno', 'platform', 
        'description', 'projectconfirmation', 'language', 'quotation_file', 
        'acceptance_file', 'created_at', 'updated_at', 'created_ip', 'updated_ip'
    ];
}