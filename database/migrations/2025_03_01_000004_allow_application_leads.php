<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
            $table->foreignId('application_id')->nullable()->unique()->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['application_id']);
            $table->dropUnique(['application_id']);
            $table->dropColumn('application_id');
        });
    }
};
