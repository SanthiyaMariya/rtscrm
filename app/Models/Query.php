<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    protected $table = 'tbl_queries';
    public $timestamps = false; // Your table has custom date fields

protected $fillable = [
    'projectname', 'productname', 'assigned_to', 'emp_name', 
    'query_title', 'query_details', 'assigned_date', 
    'target_date', 'priority', 'access_date','start_date', 'end_date', 'query_duration', 'status', 'remarks','progress_percentage'
];
}