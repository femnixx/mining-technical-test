<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('heavy_vehicles')->cascadeOnDelete();
            $table->timestamp('reported_at');
            $table->timestamp('resolved_at')->nullable();
            $table->text('issue_description');
            $table->enum('priority', ['Low', 'Medium', 'Critical'])->default('Low');
            $table->enum('status', ['Open', 'In Progress', 'Resolved'])->default('Open');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};
