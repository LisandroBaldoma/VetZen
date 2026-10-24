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
        Schema::create('assistant_knowledge_documents', function (Blueprint $table) {
            $table->id();
            $table->string('document_id')->unique();
            $table->string('path')->unique();
            $table->string('title');
            $table->string('module')->nullable()->index();
            $table->string('roles_index')->index();
            $table->json('roles');
            $table->json('route_names');
            $table->json('keywords');
            $table->string('status');
            $table->json('feature_ids');
            $table->json('source_refs');
            $table->date('reviewed_at')->nullable();
            $table->text('content');
            $table->string('content_hash', 64);
            $table->timestamps();
        });

        Schema::create('assistant_knowledge_chunks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assistant_knowledge_document_id');
            $table->foreign('assistant_knowledge_document_id', 'ak_chunks_document_fk')
                ->references('id')
                ->on('assistant_knowledge_documents')
                ->cascadeOnDelete();
            $table->unsignedSmallInteger('position');
            $table->string('heading')->nullable();
            $table->text('content');
            $table->text('search_text');
            $table->timestamps();

            $table->unique(['assistant_knowledge_document_id', 'position'], 'ak_chunks_document_position_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assistant_knowledge_chunks');
        Schema::dropIfExists('assistant_knowledge_documents');
    }
};
