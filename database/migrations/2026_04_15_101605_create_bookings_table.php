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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained();
            $table->foreignId('approver_1_id')->constrained('users');
            $table->foreignId('approver_2_id')->constrained('users');
            $table->string('driver_name');
            $table->enum('status', ['pending', 'approved', 'approved_level_1', 'rejected'])->default('pending');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('current_approval_level')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
