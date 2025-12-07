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
        Schema::create('coaches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('speciality'); // career, fitness, tech

            $table->text('description');
            $table->text('expertise')->nullable();
            $table->string('avatar')->nullable();
            $table->text('system_prompt')->nullable();
            $table->text('onboarding_questions')->nullable();

            $table->string('credentials')->nullable();
            $table->integer('years_experience')->default(0);
            $table->text('achievements')->nullable();

            $table->integer('session_start_tokens')->default(50);
            $table->boolean('subsequent_chats_free')->default(true);

            $table->boolean('is_active')->default(true);
            $table->string('total_sessions')->default(0);
            $table->decimal('average_rating',3 , 2)->default(0.00);
            $table->timestamps();

            /// Indexes
            $table->index('speciality');
            $table->index('is_active');
        });
    } 

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coaches');
    }
};
