<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resume_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resume_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('score');
            $table->text('summary');
            $table->json('strengths');
            $table->json('weaknesses');
            $table->json('recommendations');
            $table->json('categories'); // structure, clarity, experience, keywords, ats
            $table->timestamp('created_at')->useCurrent();

            $table->index(['resume_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resume_analyses');
    }
};
