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
        Schema::create('vacancy_requirements', function (Blueprint $table) {
            $table->id();
            $table->string('requirement_type', 30);
            $table->string('requirement_name');
            $table->string('requirement_value')->nullable();
            $table->decimal('weight')->nullable();
            $table->boolean('is_required')->default(1);
            $table->foreignId('vacancy_id')->constrained('vacancies')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacancy_requirements');
    }
};
