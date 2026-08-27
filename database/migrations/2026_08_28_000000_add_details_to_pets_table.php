<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->string('name')->after('client_id');
            $table->string('species', 100)->after('name');
            $table->string('breed', 100)->nullable()->after('species');
            $table->string('sex', 50)->after('breed');
            $table->date('birth_date')->nullable()->after('sex');
            $table->decimal('weight', 8, 2)->nullable()->after('birth_date');
            $table->string('color', 100)->nullable()->after('weight');
            $table->text('notes')->nullable()->after('color');
            $table->string('photo')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropColumn(['name', 'species', 'breed', 'sex', 'birth_date', 'weight', 'color', 'notes', 'photo']);
        });
    }
};
