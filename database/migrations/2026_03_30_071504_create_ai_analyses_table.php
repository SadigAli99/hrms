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
        Schema::create('ai_analyses', function (Blueprint $table) {
            $table->id();
            $table->decimal('overall_score');
            $table->decimal('skills_score')->nullable();
            $table->decimal('experience_score')->nullable();
            $table->decimal('education_score')->nullable();
            $table->decimal('languages_score')->nullable();
            $table->text('pros_text')->nullable();
            $table->text('cons_text')->nullable();
            $table->text('matched_skills_json')->nullable();
            $table->text('missing_skills_json')->nullable();
            $table->text('notes_json')->nullable();
            $table->string('analysis_version')->nullable();
            $table->dateTime('analyzed_at')->nullable();
            $table->foreignId('application_id')->constrained('candidate_applications')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_analyses');
    }
};
