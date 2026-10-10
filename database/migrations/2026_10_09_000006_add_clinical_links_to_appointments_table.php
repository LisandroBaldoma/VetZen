<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('service_request_id')->nullable()->after('service_id')->constrained()->nullOnDelete();
            $table->foreignId('treatment_session_id')->nullable()->after('service_request_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('treatment_session_id');
            $table->dropConstrainedForeignId('service_request_id');
        });
    }
};
