<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shift_dispatches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('heavy_vehicles')->cascadeOnDelete();
            $table->foreignId('operator_id')->constrained()->cascadeOnDelete();
            $table->string('pit_location');
            $table->timestamp('shift_start');
            $table->timestamp('shift_end')->nullable();
            $table->float('target_tonnage')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shift_dispatches');
    }
};
