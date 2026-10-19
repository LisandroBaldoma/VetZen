<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_schedule_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedTinyInteger('capacity');
            $table->unsignedTinyInteger('interval_minutes');
            $table->timestamps();
        });

        Schema::create('appointment_schedule_template_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_schedule_template_id');
            $table->foreign('appointment_schedule_template_id', 'astb_template_fk')
                ->references('id')->on('appointment_schedule_templates')->cascadeOnDelete();
            $table->time('starts_at');
            $table->time('ends_at');
            $table->timestamps();
            $table->unique(['appointment_schedule_template_id', 'starts_at'], 'astb_template_start_unique');
        });

        Schema::create('appointment_schedule_template_service', function (Blueprint $table) {
            $table->foreignId('appointment_schedule_template_id');
            $table->foreign('appointment_schedule_template_id', 'asts_template_fk')
                ->references('id')->on('appointment_schedule_templates')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->restrictOnDelete();
            $table->primary(['appointment_schedule_template_id', 'service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_schedule_template_service');
        Schema::dropIfExists('appointment_schedule_template_blocks');
        Schema::dropIfExists('appointment_schedule_templates');
    }
};
