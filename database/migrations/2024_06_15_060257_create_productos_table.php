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
        Schema::create('productos', function (Blueprint $table) {
            $table->id('id');
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->integer('creator_user_id');
            $table->string('category', 255)->nullable();
            $table->float('price')->nullable();
            $table->integer('stock')->nullable();
            $table->string('img_url', 255)->nullable();
            $table->string('color', 255)->nullable();
            $table->string('make', 255)->nullable();
            $table->string('model', 255)->nullable();
            $table->string('availability', 255)->nullable();
            $table->text('keywords')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
