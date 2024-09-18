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
        Schema::table('content_generates', function (Blueprint $table) {
            $table->string('file_path')->nullable()->after('status');
            $table->string('file_name')->nullable()->after('file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('content_generates', function (Blueprint $table) {
            $table->dropColumn('file_path');
            $table->dropColumn('file_name');
        });
    }
};