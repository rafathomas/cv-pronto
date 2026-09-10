<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customized_resumes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('resume_id')->constrained()->cascadeOnDelete();
            $table->foreignId('job_description_id')->constrained()->cascadeOnDelete();
            $table->text('summary')->nullable();
            $table->json('experience_descriptions');
            $table->json('highlighted_skills');
            $table->json('notes');
            $table->timestamps();

            $table->index(['user_id', 'resume_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customized_resumes');
    }
};
