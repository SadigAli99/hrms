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
        Schema::table('ai_analyses', function (Blueprint $table) {
            $table->string('analysis_type')->after('notes_json')->nullable();
            $table->text('critical_missing_json')->after('missing_skills_json')->nullable();
            $table->text('risk_flags_json')->after('critical_missing_json')->nullable();
            $table->text('requirement_matches_json')->after('risk_flags_json')->nullable();
            $table->string('seniority_fit')->after('requirement_matches_json')->nullable();
            $table->string('salary_fit')->after('seniority_fit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_analyses', function (Blueprint $table) {
            $table->dropColumn('analysis_type');
            $table->dropColumn('critical_missing_json');
            $table->dropColumn('risk_flags_json');
            $table->dropColumn('requirement_matches_json');
            $table->dropColumn('seniority_fit');
            $table->dropColumn('salary_fit');
        });
    }
};
