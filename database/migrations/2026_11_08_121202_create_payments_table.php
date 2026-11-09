<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\PaymentStatus;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->unique()
                ->constrained()
                ->restrictOnDelete();

            $table->string('transaction_id', 255)
                ->nullable()
                ->unique();

            $table->string('gateway', 50);

            $table->decimal('amount', 12, 2);

            $table->enum(
                'status',
                array_column(PaymentStatus::cases(), 'value')
            )->default(PaymentStatus::PENDING->value);

            $table->timestamp('paid_at')->nullable();

            $table->timestamp('failed_at')->nullable();

            $table->text('failure_reason')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
