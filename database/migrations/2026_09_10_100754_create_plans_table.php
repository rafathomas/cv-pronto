<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Preços e limites vivem no banco (populados a partir de config/plans.php
        // via PlanSeeder) para que possam ser alterados sem alterar código.
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // free | pro | premium
            $table->string('name');
            $table->unsignedInteger('price_cents')->default(0);
            $table->string('interval')->default('month');
            $table->json('limits');
            $table->json('features');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
