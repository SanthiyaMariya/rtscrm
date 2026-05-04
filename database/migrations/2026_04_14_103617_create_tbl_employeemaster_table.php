<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_employeemaster', function (Blueprint $table) {
            $table->id();
            $table->string('emp_name');
            $table->string('department')->nullable();
            $table->string('designation')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('pincode')->nullable();
            $table->string('state')->nullable();
            $table->string('accno')->nullable();
            $table->string('bankname')->nullable();
            $table->string('ifsc')->nullable();
            $table->string('branch')->nullable();
            $table->string('photo')->nullable();
            $table->string('aadhar')->nullable();
            $table->string('passbook')->nullable();
            $table->tinyInteger('status')->default(1); // 1=active
            $table->string('username')->unique();
            $table->string('password');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_employeemaster');
    }
};
