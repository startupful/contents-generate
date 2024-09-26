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
        if (!Schema::hasTable('logics')) {
        Schema::create('logics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('steps');
            $table->string('tags')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        }
    }

    public function down()
    {
        Schema::dropIfExists('logics');
    }
};