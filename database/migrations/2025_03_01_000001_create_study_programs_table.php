<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('study_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campus_id')->constrained()->cascadeOnDelete();
            $table->foreignId('major_id')->constrained()->cascadeOnDelete();
            $table->string('degree_level')->index();
            $table->string('degree_title')->nullable();
            $table->string('program_type')->index();
            $table->string('accreditation')->nullable();
            $table->unsignedInteger('registration_fee')->default(0);
            $table->unsignedInteger('first_payment')->default(0);
            $table->unsignedInteger('monthly_installment')->default(0);
            $table->unsignedInteger('original_monthly_installment')->nullable();
            $table->json('schedules');
            $table->json('methods');
            $table->timestamps();
            $table->unique(['campus_id', 'major_id', 'degree_level', 'program_type']);
        });

        $this->copyLegacyPivot();

        Schema::dropIfExists('campus_major');
    }

    public function down(): void
    {
        Schema::create('campus_major', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campus_id')->constrained()->cascadeOnDelete();
            $table->foreignId('major_id')->constrained()->cascadeOnDelete();
            $table->unique(['campus_id', 'major_id']);
            $table->timestamps();
        });

        DB::table('campus_major')->insertUsing(
            ['campus_id', 'major_id'],
            DB::table('study_programs')->select('campus_id', 'major_id')->distinct(),
        );

        Schema::dropIfExists('study_programs');
    }

    /**
     * Data lama tidak punya jenjang atau biaya, jadi diisi nilai awal yang aman
     * dan dapat dikoreksi oleh pemilik kampus.
     */
    private function copyLegacyPivot(): void
    {
        if (! Schema::hasTable('campus_major')) {
            return;
        }

        DB::table('study_programs')->insertUsing(
            [
                'campus_id', 'major_id', 'degree_level', 'program_type',
                'accreditation', 'schedules', 'methods', 'created_at', 'updated_at',
            ],
            DB::table('campus_major')
                ->join('campuses', 'campuses.id', '=', 'campus_major.campus_id')
                ->select(
                    'campus_major.campus_id',
                    'campus_major.major_id',
                    DB::raw("'s1'"),
                    DB::raw("'reguler'"),
                    'campuses.accreditation',
                    DB::raw('\'["pagi"]\''),
                    DB::raw('\'["tatap-muka"]\''),
                    DB::raw('CURRENT_TIMESTAMP'),
                    DB::raw('CURRENT_TIMESTAMP'),
                ),
        );
    }
};
