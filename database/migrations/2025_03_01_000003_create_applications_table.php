<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('campus_id')->constrained()->cascadeOnDelete();
            $table->foreignId('major_id')->constrained()->cascadeOnDelete();
            $table->string('full_name');
            $table->string('email')->index();
            $table->string('whatsapp', 20);
            $table->string('last_education');
            $table->string('region');
            $table->string('program_type');
            $table->string('schedule');
            $table->string('source_info');
            $table->boolean('accepted_terms');
            $table->string('status')->default('new')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
