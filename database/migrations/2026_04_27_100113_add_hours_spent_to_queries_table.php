<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tbl_queries', function (Blueprint $table) {
            $table->decimal('hours_spent', 8, 2)->default(0)->after('progress_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_queries', function (Blueprint $table) {
             $table->dropColumn('hours_spent');
            //
        });
    }
};
