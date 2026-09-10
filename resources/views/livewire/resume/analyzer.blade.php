<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    <div class="flex items-center justify-between mb-6 gap-4 flex-wrap">
        <div>
            <p class="text-xs font-bold uppercase tracking-wide text-accent-dark">/analisar-curriculo</p>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Análise do seu currículo</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $resume->title }}</p>
        </div>
        <button type="button" wire:click="analyze" wire:loading.attr="disabled" wire:target="analyze"
            class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-accent hover:bg-accent-dark disabled:opacity-60 rounded-lg px-4 py-2.5 transition">
            <svg wire:loading.remove wire:target="analyze" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 2 3 14h7l-1 8 10-12h-7l1-8Z"/></svg>
            <svg wire:loading wire:target="analyze" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
            <span wire:loading.remove wire:target="analyze">{{ $analysis ? 'Analisar novamente' : 'Analisar meu currículo' }}</span>
            <span wire:loading wire:target="analyze">Analisando...</span>
        </button>
    </div>

    @error('ai')
        <div class="mb-6 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3">
            {{ $message }}
        </div>
    @enderror

    @if (! $analysis)
        <div class="bg-white border border-dashed border-gray-300 rounded-xl p-10 text-center">
            <p class="text-gray-500">Você ainda não analisou este currículo.</p>
            <p class="text-sm text-gray-400 mt-1">Clique em "Analisar meu currículo" para receber uma nota de 0 a 100 com recomendações objetivas.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-[auto_1fr] gap-8 items-center mb-8 bg-white border border-gray-200 rounded-xl p-6">
            <div class="mx-auto sm:mx-0 relative w-40 h-40">
                <svg viewBox="0 0 36 36" class="w-40 h-40 -rotate-90">
                    <circle cx="18" cy="18" r="16" fill="none" stroke="#E9EEF5" stroke-width="3"></circle>
                    <circle cx="18" cy="18" r="16" fill="none" stroke="#16A34A" stroke-width="3" stroke-linecap="round"
                        stroke-dasharray="{{ round($analysis->score * 100.53 / 100, 1) }} 100.53" />
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-4xl font-bold text-gray-900">{{ $analysis->score }}</span>
                    <span class="text-xs text-gray-500">de 100</span>
                </div>
            </div>
            <div>
                <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-full
                    {{ $analysis->score >= 70 ? 'bg-green-50 text-accent-dark' : ($analysis->score >= 50 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700') }}">
                    {{ $analysis->score >= 70 ? 'Muito bom' : ($analysis->score >= 50 ? 'Precisa de ajustes' : 'Requer atenção') }}
                </span>
                <p class="text-sm text-gray-700 mt-3">{{ $analysis->summary }}</p>
                <p class="text-xs text-gray-400 mt-3">Analisado {{ $analysis->created_at->diffForHumans() }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-8">
            @foreach ([
                'structure' => 'Estrutura',
                'clarity' => 'Clareza',
                'experience' => 'Experiência',
                'keywords' => 'Palavras-chave',
                'ats' => 'ATS',
            ] as $key => $label)
                <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                    <p class="text-xl font-bold {{ ($analysis->categories[$key] ?? 0) < 60 ? 'text-amber-600' : 'text-gray-900' }}">{{ $analysis->categories[$key] ?? '—' }}</p>
                    <p class="text-xs text-gray-500 font-semibold mt-1">{{ $label }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <h3 class="font-semibold text-gray-900 mb-3">Pontos fortes</h3>
                <ul class="space-y-2">
                    @foreach ($analysis->strengths as $item)
                        <li class="flex gap-2 text-sm text-gray-700">
                            <svg class="w-4 h-4 text-accent-dark shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5"/></svg>
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <h3 class="font-semibold text-gray-900 mb-3">Pontos de atenção</h3>
                <ul class="space-y-2">
                    @foreach ($analysis->weaknesses as $item)
                        <li class="flex gap-2 text-sm text-gray-700">
                            <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4M12 17h.01M10.3 3.9 2 18a2 2 0 0 0 1.7 3h16.6a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/></svg>
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div>
            <h3 class="font-semibold text-gray-900 mb-3">Recomendações</h3>
            <div class="space-y-3">
                @foreach ($analysis->recommendations as $i => $item)
                    <div class="bg-white border border-gray-200 rounded-xl p-4 flex gap-3 text-sm text-gray-700">
                        <span class="w-6 h-6 rounded-md bg-gray-100 text-gray-700 text-xs font-bold flex items-center justify-center shrink-0">{{ $i + 1 }}</span>
                        {{ $item }}
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
