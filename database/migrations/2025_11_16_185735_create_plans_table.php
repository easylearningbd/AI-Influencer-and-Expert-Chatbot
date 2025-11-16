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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // Basic Plan
            $table->string('slug')->unique(); // basic-plan
            $table->text('description')->nullable();
            $table->integer('tokens');
            $table->decimal('price',10,2);
            $table->enum('type',['one-time','monthly','yearly']);
            $table->boolean('is_active')->default(true);
            $table->json('features')->nullable();
            $table->integer('sort_order')->default(0); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
