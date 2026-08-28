<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clinical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained()->restrictOnDelete();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('updated_by')->constrained('users')->restrictOnDelete();
            $table->string('type', 50);
            $table->string('title');
            $table->text('content');
            $table->dateTime('occurred_at');
            $table->boolean('is_visible_to_client');
            $table->timestamps();

            $table->index(['pet_id', 'occurred_at', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinical_records');
    }
};
