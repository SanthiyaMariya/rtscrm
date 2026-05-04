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
    Schema::table('tbl_query_logs', function (Blueprint $table) {
        // 1. Add the missing status column
        $table->integer('status')->after('query_id')->nullable();

        // 2. Rename 'percentage' to 'progress_percentage' to match your code
        $table->renameColumn('percentage', 'progress_percentage');
    });
}

public function down(): void
{
    Schema::table('tbl_query_logs', function (Blueprint $table) {
        $table->dropColumn('status');
        $table->renameColumn('progress_percentage', 'percentage');
    });
}
};
