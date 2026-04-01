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
        Schema::create('candidate_profiles', function (Blueprint $table) {
            $table->id();
            $table->text('professional_summary')->nullable();
            $table->text('skills_json')->nullable();
            $table->text('experience_json')->nullable();
            $table->text('education_json')->nullable();
            $table->text('languages_json')->nullable();
            $table->text('certifications_json')->nullable();
            $table->string('parsed_source', 30);
            $table->string('parser_version', 50)->nullable();
            $table->dateTime('last_parsed_at')->nullable();
            $table->foreignId('candidate_id')->constrained('candidates')->onDelete('cascade');
            $table->foreignId('cv_file_id')->nullable()->constrained('candidate_cv_files')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_profiles');
    }
};
