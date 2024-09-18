<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('content_generates', function (Blueprint $table) {
            $table->longText('audio_content')->nullable();
            $table->text('audio_text')->nullable();
            $table->string('audio_model')->nullable();
            $table->string('audio_voice')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('content_generates', function (Blueprint $table) {
            $table->dropColumn(['audio_content', 'audio_text', 'audio_model', 'audio_voice']);
        });
    }
};