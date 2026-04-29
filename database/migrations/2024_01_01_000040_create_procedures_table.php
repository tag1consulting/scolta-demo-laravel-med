<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('procedures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category'); // first_aid, surgical, diagnostic, therapeutic
            $table->enum('risk_level', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->text('description');
            $table->text('indications');
            $table->text('contraindications')->nullable();
            $table->text('equipment_standard');
            $table->text('equipment_lunar')->nullable();
            $table->text('steps');
            $table->text('steps_lunar')->nullable();
            $table->text('telemedicine_points')->nullable();
            $table->text('training_requirements')->nullable();
            $table->text('complications')->nullable();
            $table->text('search_keywords')->nullable();
            $table->boolean('enriched')->default(false);
            $table->timestamps();

            $table->index('category');
            $table->index('risk_level');
            $table->index('slug');
            $table->fullText(['name', 'description', 'indications', 'steps'], 'procs_ft_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procedures');
    }
};
