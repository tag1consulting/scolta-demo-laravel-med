<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('research_type'); // case_study, equipment_review, policy, clinical_trial, perspective, editorial
            $table->string('author_name');
            $table->string('author_affiliation')->nullable();
            $table->text('abstract');
            $table->longText('content');
            $table->string('journal_name')->nullable();
            $table->string('volume_issue')->nullable();
            $table->date('published_date')->nullable();
            $table->text('keywords')->nullable();
            $table->text('references')->nullable();
            $table->string('body_system')->nullable();
            $table->text('search_keywords')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('enriched')->default(false);
            $table->timestamps();

            $table->index('research_type');
            $table->index('body_system');
            $table->index('featured');
            $table->index('slug');
            $table->fullText(['title', 'abstract', 'content', 'keywords'], 'articles_ft_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
