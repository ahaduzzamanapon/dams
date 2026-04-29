<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->string('patient_name');
            $table->string('patient_phone', 20);
            $table->date('appointment_date');
            $table->time('slot_time');
            $table->string('status')->default('pending')->comment('pending,confirmed,cancelled,completed');
            $table->text('notes')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();

            $table->index(['doctor_id', 'appointment_date', 'status']);
            $table->index(['appointment_date', 'slot_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
