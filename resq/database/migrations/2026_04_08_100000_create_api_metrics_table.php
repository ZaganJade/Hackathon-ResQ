<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for API Metrics table
 * Task 13.9 - API Usage Monitoring
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_metrics', function (Blueprint $table) {
            $table->id();
            $table->string('service', 50)->index(); // fireworks, google_maps, whatsapp
            $table->string('endpoint', 255);
            $table->decimal('response_time_ms', 10, 2);
            $table->boolean('success')->default(true)->index();
            $table->unsignedSmallInteger('status_code')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('created_at');

            // Indexes for performance
            $table->index(['service', 'created_at']);
            $table->index(['success', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_metrics');
    }
};
