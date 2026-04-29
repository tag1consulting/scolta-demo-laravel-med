<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anatomies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('body_system');
            $table->string('structure_type'); // organ, tissue, bone, vessel, nerve, etc.
            $table->text('normal_function');
            $table->text('description');
            $table->text('lunar_adaptation_arrival')->nullable();   // first weeks
            $table->text('lunar_adaptation_6m')->nullable();        // 6-month resident
            $table->text('lunar_adaptation_2y')->nullable();        // long-term resident
            $table->text('common_conditions')->nullable();          // JSON array of condition slugs
            $table->text('cross_references')->nullable();
            $table->text('search_keywords')->nullable();
            $table->boolean('enriched')->default(false);
            $table->timestamps();

            $table->index('body_system');
            $table->index('slug');
            $table->fullText(['name', 'description', 'normal_function']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anatomies');
    }
};
