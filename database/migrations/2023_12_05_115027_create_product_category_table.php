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
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable();
            $table->float('price')->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->integer('qty')->nullable();
            $table->float('weight')->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_category');
    }
};
