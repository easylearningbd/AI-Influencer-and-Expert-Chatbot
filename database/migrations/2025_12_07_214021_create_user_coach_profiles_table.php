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
        Schema::create('user_coach_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('coach_id')->constrained()->onDelete('cascade');

            // Personal Information (collected during onboarding)
            $table->text('profile_data')->nullable(); // JSON: age, occupation, industry, experience_level, etc.

            // Career-specific (for Career Coach)
            $table->string('current_role')->nullable();
            $table->string('target_role')->nullable();
            $table->integer('years_experience')->nullable();
            $table->text('skills')->nullable(); // JSON array
            $table->text('career_aspirations')->nullable();

            // Fitness-specific (for Fitness Coach)
            $table->string('fitness_level')->nullable(); // beginner, intermediate, advanced
            $table->decimal('height', 5, 2)->nullable(); // in cm
            $table->decimal('weight', 5, 2)->nullable(); // in kg
            $table->string('gender')->nullable();
            $table->integer('age')->nullable();
            $table->text('health_conditions')->nullable(); // JSON array
            $table->text('fitness_goals')->nullable(); // lose weight, gain muscle, improve endurance
            $table->text('workout_preferences')->nullable(); // JSON: gym/home, equipment available

            // Finance-specific (for Finance Advisor)
            $table->decimal('monthly_income', 10, 2)->nullable();
            $table->decimal('monthly_expenses', 10, 2)->nullable();
            $table->decimal('savings', 12, 2)->nullable();
            $table->decimal('debt', 12, 2)->nullable();
            $table->string('risk_tolerance')->nullable(); // conservative, moderate, aggressive
            $table->text('financial_goals')->nullable(); // JSON array
            $table->text('investment_experience')->nullable();

            // Nutrition-specific (for Personal Chef)
            $table->text('dietary_restrictions')->nullable(); // JSON: vegetarian, vegan, gluten-free, etc.
            $table->text('food_allergies')->nullable(); // JSON array
            $table->text('liked_foods')->nullable(); // JSON array
            $table->text('disliked_foods')->nullable(); // JSON array
            $table->integer('daily_calorie_goal')->nullable();
            $table->text('meal_preferences')->nullable(); // JSON: meal types, cooking time, etc.

            // Onboarding status
            $table->boolean('onboarding_completed')->default(false);
            $table->timestamp('onboarding_completed_at')->nullable();
            $table->text('onboarding_answers')->nullable(); // JSON: all questionnaire answers
            $table->timestamps();

            // Indexes
            $table->unique(['user_id','coach_id']);
            $table->index('onboarding_completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_coach_profiles');
    }
};
