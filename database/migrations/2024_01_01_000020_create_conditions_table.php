<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conditions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('lunar_variant_name')->nullable();
            $table->string('icd10_code')->nullable();
            $table->string('body_system');
            $table->enum('severity', ['minor', 'moderate', 'severe', 'critical'])->default('moderate');
            $table->text('description');
            $table->text('lunar_risk_factors')->nullable();
            $table->text('symptoms');
            $table->text('lunar_symptoms')->nullable();
            $table->text('diagnosis');
            $table->text('treatment');
            $table->text('treatment_lunar')->nullable();
            $table->text('evacuation_criteria')->nullable();
            $table->text('prevention')->nullable();
            $table->text('cross_references')->nullable(); // JSON array of related slugs
            $table->text('search_keywords')->nullable();
            $table->boolean('is_emergency')->default(false);
            $table->boolean('enriched')->default(false);
            $table->timestamps();

            $table->index('body_system');
            $table->index('severity');
            $table->index('is_emergency');
            $table->index('slug');
            $table->fullText(['name', 'symptoms', 'description', 'lunar_risk_factors']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conditions');
    }
};
