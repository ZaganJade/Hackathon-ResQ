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
        Schema::create('disasters', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['earthquake', 'flood', 'volcano', 'tsunami', 'landslide', 'fire', 'drought', 'other']);
            $table->string('location');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('low');
            $table->enum('status', ['active', 'monitoring', 'resolved'])->default('active');
            $table->text('description')->nullable();
            $table->enum('source', ['manual', 'bmkg_api'])->default('manual');
            $table->string('source_id')->nullable();
            $table->json('raw_data')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index(['type', 'status']);
            $table->index(['severity', 'status']);
            $table->index(['latitude', 'longitude']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disasters');
    }
};
