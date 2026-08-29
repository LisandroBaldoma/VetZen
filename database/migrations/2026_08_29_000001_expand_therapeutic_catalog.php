<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['duration_minutes', 'price', 'currency', 'modalities']);
        });

        Schema::create('procedures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['service_id', 'name']);
        });

        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->text('description');
            $table->unsignedInteger('estimated_sessions');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['service_id', 'name']);
        });

        Schema::create('procedure_treatment', function (Blueprint $table) {
            $table->foreignId('procedure_id')->constrained()->restrictOnDelete();
            $table->foreignId('treatment_id')->constrained()->cascadeOnDelete();
            $table->primary(['procedure_id', 'treatment_id']);
        });

        Schema::create('pet_treatments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained()->restrictOnDelete();
            $table->foreignId('treatment_id')->constrained()->restrictOnDelete();
            $table->string('treatment_name');
            $table->text('treatment_description');
            $table->unsignedInteger('planned_sessions');
            $table->decimal('default_session_price', 12, 2);
            $table->string('currency', 3)->default('ARS');
            $table->date('starts_on');
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['pet_id', 'starts_on']);
        });

        Schema::create('pet_treatment_procedures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_treatment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('procedure_id')->nullable()->constrained()->nullOnDelete();
            $table->string('procedure_name');
            $table->text('procedure_description')->nullable();
            $table->timestamps();
            $table->unique(['pet_treatment_id', 'procedure_id']);
        });

        Schema::create('treatment_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_treatment_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('session_number');
            $table->dateTime('scheduled_at')->nullable();
            $table->decimal('price', 12, 2);
            $table->string('currency', 3)->default('ARS');
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['pet_treatment_id', 'session_number']);
            $table->index(['pet_treatment_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treatment_sessions');
        Schema::dropIfExists('pet_treatment_procedures');
        Schema::dropIfExists('pet_treatments');
        Schema::dropIfExists('procedure_treatment');
        Schema::dropIfExists('treatments');
        Schema::dropIfExists('procedures');

        Schema::table('services', function (Blueprint $table) {
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->string('currency', 3)->default('ARS');
            $table->json('modalities')->default('[]');
        });
    }
};
