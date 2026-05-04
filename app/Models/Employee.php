<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use Notifiable;

    protected $table = 'tbl_employeemaster'; 
    public $timestamps = false; 

    protected $fillable = [
        'emp_name', 'department', 'designation', 'mobile', 'email', 'address', 'city',
        'pincode', 'state', 'accno', 'bankname', 'ifsc', 'branch', 'photo', 'aadhar',
        'passbook', 'status', 'username', 'password', 'confirm_password', 'created_at'
    ];

    protected $hidden = [
        'password', 'confirm_password',
    ];
}