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
        Schema::create('guides', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('category', ['earthquake', 'flood', 'volcano', 'tsunami', 'landslide', 'fire', 'drought', 'general', 'storm', 'heatwave', 'wilderness', 'first-aid']);
            $table->longText('content');
            $table->json('steps')->nullable();
            $table->string('image')->nullable();
            $table->string('video_url')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamps();

            // Indexes
            $table->index('slug');
            $table->index('category');
            $table->index(['status', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guides');
    }
};
