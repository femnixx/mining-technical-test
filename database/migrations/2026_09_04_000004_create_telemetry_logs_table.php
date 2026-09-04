<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telemetry_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('heavy_vehicles')->cascadeOnDelete();
            $table->timestamp('timestamp');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->float('speed_kmh')->nullable();
            $table->float('fuel_level_pct')->nullable();
            $table->float('engine_temp_c')->nullable();
            $table->float('load_tonnage')->nullable();
            $table->timestamps();

            $table->index(['vehicle_id', 'timestamp']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telemetry_logs');
    }
};
