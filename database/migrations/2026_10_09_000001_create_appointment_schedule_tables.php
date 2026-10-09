<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_schedule_dates', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->unsignedTinyInteger('capacity');
            $table->timestamps();
        });

        Schema::create('appointment_schedule_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_schedule_date_id');
            $table->foreign('appointment_schedule_date_id', 'asb_schedule_date_fk')
                ->references('id')->on('appointment_schedule_dates')->cascadeOnDelete();
            $table->time('starts_at');
            $table->time('ends_at');
            $table->timestamps();
            $table->unique(['appointment_schedule_date_id', 'starts_at'], 'asb_date_start_unique');
        });

        Schema::create('appointment_schedule_starts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_schedule_date_id');
            $table->foreign('appointment_schedule_date_id', 'ass_schedule_date_fk')
                ->references('id')->on('appointment_schedule_dates')->cascadeOnDelete();
            $table->time('starts_at');
            $table->timestamps();
            $table->unique(['appointment_schedule_date_id', 'starts_at'], 'ass_date_start_unique');
        });

        Schema::create('appointment_schedule_date_service', function (Blueprint $table) {
            $table->foreignId('appointment_schedule_date_id');
            $table->foreign('appointment_schedule_date_id', 'asds_schedule_date_fk')
                ->references('id')->on('appointment_schedule_dates')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->restrictOnDelete();
            $table->primary(['appointment_schedule_date_id', 'service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_schedule_date_service');
        Schema::dropIfExists('appointment_schedule_starts');
        Schema::dropIfExists('appointment_schedule_blocks');
        Schema::dropIfExists('appointment_schedule_dates');
    }
};
