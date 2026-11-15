<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_category_tiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')->unique()->constrained()->cascadeOnDelete();

            $table->string('image_path', 500);

            $table->string('background_color', 7)->unique();

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_category_tiles');
    }
};