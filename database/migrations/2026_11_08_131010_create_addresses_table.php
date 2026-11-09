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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();

            // Relationship with users
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Address information
            $table->string('title', 100);
            $table->string('recipient_name')->nullable();
            $table->string('phone', 20);

            $table->string('province', 100);
            $table->string('city', 100);
            $table->string('postal_code', 20);
            $table->text('address');

            $table->boolean('is_default')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
