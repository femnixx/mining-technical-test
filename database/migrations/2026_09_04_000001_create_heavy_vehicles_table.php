<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('heavy_vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_code')->unique();
            $table->enum('type', ['Haul Truck', 'Excavator', 'Dozer']);
            $table->string('model');
            $table->enum('status', ['Active', 'Maintenance', 'Idle'])->default('Idle');
            $table->float('fuel_capacity_l')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('heavy_vehicles');
    }
};
