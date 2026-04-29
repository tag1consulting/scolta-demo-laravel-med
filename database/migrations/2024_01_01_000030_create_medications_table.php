<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->string('generic_name');
            $table->string('slug')->unique();
            $table->string('brand_names')->nullable(); // comma-separated
            $table->string('drug_class');
            $table->text('mechanism');
            $table->text('indications');
            $table->text('dosing_standard');
            $table->text('dosing_lunar')->nullable();
            $table->text('storage_standard')->nullable();
            $table->text('storage_lunar')->nullable();
            $table->text('supply_chain_notes')->nullable();
            $table->text('interactions')->nullable();
            $table->text('contraindications')->nullable();
            $table->text('side_effects')->nullable();
            $table->text('alternatives')->nullable();
            $table->boolean('who_essential')->default(false);
            $table->boolean('lunar_critical')->default(false);
            $table->text('search_keywords')->nullable();
            $table->boolean('enriched')->default(false);
            $table->timestamps();

            $table->index('drug_class');
            $table->index('lunar_critical');
            $table->index('slug');
            $table->fullText(['generic_name', 'indications', 'drug_class', 'mechanism'], 'meds_ft_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};
