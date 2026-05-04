<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    protected $table = 'tbl_dailyreportspecial'; // Matches your migration file

    protected $fillable = [
        'emp_name', 
        'date', 
        'type', // Will store 'Report', 'Leave', 'WFH', 'OT', 'Permission'
        'notes'
    ];
      
}