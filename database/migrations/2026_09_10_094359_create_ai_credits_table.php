<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ledger de créditos de IA: entradas positivas (concessões) e negativas
        // (consumos). O saldo é a soma das concessões não expiradas com todos
        // os consumos. Ver App\Domain\AI\Services\AiCreditService.
        Schema::create('ai_credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // grant | consumption
            $table->integer('amount'); // positivo (concessão) ou negativo (consumo)
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_credits');
    }
};
