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
            $table->increments('id');
            $table->string('name', 255);
            $table->text('description');
            $table->integer('creator_user_id');
            $table->string('category', 255);
            $table->float('price');
            $table->integer('stock');
            $table->string('img_url', 255);
            $table->string('color', 255);
            $table->string('make', 255);
            $table->string('model', 255);
            $table->string('availability', 255);
            $table->text('keywords');
            $table->tinyInteger('deleted');
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
