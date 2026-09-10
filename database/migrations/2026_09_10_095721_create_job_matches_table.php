<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('resume_id')->constrained()->cascadeOnDelete();
            $table->foreignId('job_description_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('match_score');
            $table->json('matched_skills');
            $table->json('partial_skills');
            $table->json('missing_skills');
            $table->json('keywords');
            $table->json('recommendations');
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_matches');
    }
};
