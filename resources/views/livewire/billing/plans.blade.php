<div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    <p class="text-xs font-bold uppercase tracking-wide text-accent-dark">Planos</p>
    <h1 class="text-2xl font-bold text-gray-900 mt-1">Escolha o plano ideal para você</h1>
    <p class="text-sm text-gray-500 mt-1">Seu plano atual: <strong>{{ $currentPlan->name }}</strong></p>

    @error('billing')
        <div class="mt-4 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3">{{ $message }}</div>
    @enderror

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mt-6">
        @foreach ($plans as $plan)
            <div class="bg-white border-2 rounded-xl p-6 flex flex-col gap-4 {{ $plan->id === $currentPlan->id ? 'border-accent' : 'border-gray-200' }}">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wide text-gray-400">{{ $plan->name }}</span>
                    <p class="text-2xl font-bold text-gray-900 mt-1">
                        R$ {{ number_format($plan->price_cents / 100, 2, ',', '.') }}
                        @if ($plan->price_cents > 0)
                            <span class="text-sm font-medium text-gray-400">/mês</span>
                        @endif
                    </p>
                </div>
                <ul class="text-sm text-gray-600 space-y-2 flex-1">
                    @foreach ($plan->features as $feature)
                        <li class="flex gap-2">
                            <svg class="w-4 h-4 text-accent-dark shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5"/></svg>
                            {{ str_replace('_', ' ', $feature) }}
                        </li>
                    @endforeach
                </ul>

                @if ($plan->id === $currentPlan->id)
                    <span class="text-center text-sm font-semibold text-accent-dark bg-green-50 rounded-lg px-4 py-2.5">Plano atual</span>
                @elseif ($plan->price_cents === 0)
                    <span class="text-center text-sm font-semibold text-gray-400 bg-gray-50 rounded-lg px-4 py-2.5">Gratuito</span>
                @else
                    <button type="button" wire:click="subscribe({{ $plan->id }})" wire:loading.attr="disabled" wire:target="subscribe({{ $plan->id }})"
                        class="text-sm font-semibold text-white bg-accent hover:bg-accent-dark disabled:opacity-60 rounded-lg px-4 py-2.5 transition">
                        Assinar {{ $plan->name }}
                    </button>
                @endif
            </div>
        @endforeach
    </div>

</div>
