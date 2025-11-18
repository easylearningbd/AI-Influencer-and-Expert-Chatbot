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
        Schema::create('influencers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('avatar')->nullable();
            $table->string('niche')->nullable();
            $table->text('bio')->nullable();
            $table->text('style')->nullable();
            $table->text('system_prompt')->nullable();
            $table->text('image_prompt')->nullable();
            $table->string('youtube_link')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('can_generate_image')->default(true);
            $table->integer('chat_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('influencers');
    }
};
