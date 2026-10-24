<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('appointment_link_histories')) {
            Schema::create('appointment_link_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
                $table->foreignId('changed_by')->constrained('users')->restrictOnDelete();
                $table->foreignId('old_service_request_id')->nullable()->constrained('service_requests')->nullOnDelete();
                $table->foreignId('new_service_request_id')->nullable()->constrained('service_requests')->nullOnDelete();
                $table->foreignId('old_treatment_session_id')->nullable()->constrained('treatment_sessions')->nullOnDelete();
                $table->foreignId('new_treatment_session_id')->nullable()->constrained('treatment_sessions')->nullOnDelete();
                $table->timestamp('created_at');
                $table->index(['appointment_id', 'created_at']);
            });
        }

        if (! Schema::hasTable('appointment_reminder_deliveries')) {
            Schema::create('appointment_reminder_deliveries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('appointment_id')->constrained()->cascadeOnDelete();
                $table->dateTime('appointment_starts_at');
                $table->timestamp('sent_at');
                $table->unique(['appointment_id', 'appointment_starts_at'], 'appointment_reminder_unique');
            });
        } else {
            Schema::table('appointment_reminder_deliveries', function (Blueprint $table) {
                $table->unique(['appointment_id', 'appointment_starts_at'], 'appointment_reminder_unique');
            });
        }

        if (! Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('appointment_reminder_deliveries');
        Schema::dropIfExists('appointment_link_histories');
    }
};
