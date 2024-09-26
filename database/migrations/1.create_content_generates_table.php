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
        if (!Schema::hasTable('content_generates')) {
            Schema::create('content_generates', function (Blueprint $table) {
                $table->id();
                $table->string('uuid');
                $table->string('title');
                $table->text('content');
                $table->string('type');
                $table->string('status')->default('pending');
                $table->dateTime('published_date')->nullable();
                $table->text('audio_content')->nullable();
                $table->text('audio_text')->nullable();
                $table->string('audio_model')->nullable();
                $table->string('audio_voice')->nullable();
                $table->string('file_path')->nullable();
                $table->string('file_name')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_generates');
    }
};