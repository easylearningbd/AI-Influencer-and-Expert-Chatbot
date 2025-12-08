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
        Schema::create('user_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('coach_id')->constrained()->onDelete('cascade');

            // Goal details
            $table->string('title'); // e.g., "Get promoted to Senior Developer"
            $table->text('description')->nullable();
            $table->string('category'); // career, fitness, financial, personal
            $table->string('status')->default('in_progress'); // in_progress, completed, paused, abandoned

            // Timeline
            $table->date('target_date')->nullable();
            $table->date('started_at')->nullable();
            $table->date('completed_at')->nullable();

            // Progress tracking
            $table->integer('progress_percentage')->default(0); // 0-100
            $table->text('milestones')->nullable(); // JSON array of sub-goals
            $table->text('completed_milestones')->nullable(); // JSON array

            // Metrics (depends on goal type)
            $table->text('metrics')->nullable(); // JSON: custom tracking metrics
            $table->text('progress_history')->nullable(); // JSON: timeline of progress updates

            // Coach notes
            $table->text('coach_notes')->nullable();
            $table->text('action_plan')->nullable(); // JSON array of action items

            // Priority
            $table->string('priority')->default('medium'); // low, medium, high
            $table->timestamps();

            // Indexes 
            $table->index(['user_id','coach_id']);
            $table->index('status');
            $table->index('target_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_goals');
    }
};
