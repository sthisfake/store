<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\ProductStatus;
use App\Enums\ProductType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('name', 255);

            $table->string('slug', 255)->unique();

            $table->string('sku', 100)
                ->nullable()
                ->unique();

            $table->longText('description')->nullable();

            $table->decimal('price', 12, 2);

            $table->enum(
                'status',
                array_column(ProductStatus::cases(), 'value')
            )->default(ProductStatus::ACTIVE->value);

            $table->enum(
                'product_type',
                array_column(ProductType::cases(), 'value')
            );

            $table->unsignedInteger('stock_quantity')->default(0);

            $table->boolean('track_inventory')->default(true);

            $table->timestamps();

            $table->softDeletes();

            $table->index(['category_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
