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
        Schema::create('coach_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('coach_id')->constrained()->onDelete('cascade');
            $table->string('session_id')->unique(); // Unique session identifier

            // Session details
            $table->timestamp('started_at');
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->boolean('is_first_session')->default(false);

            // Pricing
            $table->integer('tokens_charged')->default(0); // 50 for first, 0 for subsequent
            $table->boolean('was_charged')->default(false);

            // Session metrics
            $table->integer('total_messages')->default(0);
            $table->integer('total_tokens_used')->default(0);
            $table->integer('duration_minutes')->default(0);

            // Session goal/focus
            $table->string('session_topic')->nullable(); // What user wanted to discuss
            $table->text('session_summary')->nullable(); // AI-generated summary at end
            $table->text('action_items')->nullable(); // JSON array of takeaways/next steps
            $table->decimal('user_satisfaction', 3, 2)->nullable(); // Optional rating 0-5

            // Status
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['user_id','coach_id']);
            $table->index('session_id');
            $table->index('is_active');
            $table->index('started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coach_sessions');
    }
};
