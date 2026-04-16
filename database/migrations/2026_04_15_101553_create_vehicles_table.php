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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('model_name');
            $table->string('plate_number')->unique();
            $table->enum('type', ['passenger', 'cargo']);
            $table->enum('ownership', ['owned', 'rented']);
            $table->foreignId('location_id')->constrained('locations');
            $table->float('fuel_consumption');
            $table->date('last_service_at')->nullable();
            $table->boolean('is_available')->default(true);
            $table->float('distance_km');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
