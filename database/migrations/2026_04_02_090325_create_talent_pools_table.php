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
        Schema::create('talent_pools', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->text('note')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('added_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('source_application_id')->constrained('candidate_applications')->onDelete('cascade');
            $table->foreignId('source_vacancy_id')->constrained('vacancies')->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained('candidates')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talent_pools');
    }
};
