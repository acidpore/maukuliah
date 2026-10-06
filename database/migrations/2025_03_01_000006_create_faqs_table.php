<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('scope_type')->index();
            $table->string('scope_key')->nullable();
            $table->string('question');
            $table->text('answer');
            $table->unsignedSmallInteger('sort')->default(0);
            $table->timestamps();
            $table->index(['scope_type', 'scope_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
