<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assistant_knowledge_documents', function (Blueprint $table) {
            $table->string('grounding')->default('allowed');
        });
    }

    public function down(): void
    {
        Schema::table('assistant_knowledge_documents', function (Blueprint $table) {
            $table->dropColumn('grounding');
        });
    }
};
