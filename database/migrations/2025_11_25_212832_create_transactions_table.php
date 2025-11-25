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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('plan_id')->nullable()->constrained('plans')->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained('plan_id')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->integer('tokens');
            $table->enum('payment_method',['bank_transfer','card','stripe'])->default('bank_transfer');
            $table->enum('status',['pending','completed','rejected','refunded'])->default('pending');

            $table->string('transaction_type')->default('one-time');
            $table->text('payment_proof')->nullable();
            $table->text('bank_details')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
