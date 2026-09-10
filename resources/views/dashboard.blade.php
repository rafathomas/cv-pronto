<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Saudação --}}
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Olá, {{ explode(' ', auth()->user()->name)[0] }} 👋</h1>
                    <p class="text-sm text-gray-500 mt-1">
                        @if ($resume)
                            {{ $resume->title }} · atualizado {{ $resume->updated_at->diffForHumans() }}
                        @else
                            Vamos criar seu primeiro currículo?
                        @endif
                    </p>
                </div>
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-700 bg-white border border-gray-200 rounded-full px-3 py-1.5">
                    <svg class="w-3.5 h-3.5 text-accent-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 2 3 14h7l-1 8 10-12h-7l1-8Z"/></svg>
                    {{ $creditsBalance }} {{ Str::plural('crédito', $creditsBalance) }} de IA
                </span>
            </div>

            {{-- Atalhos --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('resume.builder', $resume) }}" wire:navigate
                    class="group bg-white border border-gray-200 rounded-xl p-5 hover:border-accent hover:shadow-sm transition">
                    <span class="w-9 h-9 rounded-lg bg-brand/10 text-brand flex items-center justify-center">
                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
                    </span>
                    <h3 class="font-semibold text-gray-900 mt-3">{{ $resume ? 'Editar currículo' : 'Criar currículo' }}</h3>
                    <p class="text-sm text-gray-500 mt-1">Preencha seus dados, seção por seção.</p>
                </a>

                <a href="{{ route('resume.import') }}" wire:navigate
                    class="group bg-white border border-gray-200 rounded-xl p-5 hover:border-accent hover:shadow-sm transition">
                    <span class="w-9 h-9 rounded-lg bg-brand/10 text-brand flex items-center justify-center">
                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0-4-4m4 4 4-4M4 21h16"/></svg>
                    </span>
                    <h3 class="font-semibold text-gray-900 mt-3">Importar currículo</h3>
                    <p class="text-sm text-gray-500 mt-1">Envie um PDF ou DOCX existente.</p>
                </a>

                <a href="{{ route('resume.analyze', $resume) }}" wire:navigate
                    class="group bg-white border border-gray-200 rounded-xl p-5 hover:border-accent hover:shadow-sm transition">
                    <span class="w-9 h-9 rounded-lg bg-accent/10 text-accent-dark flex items-center justify-center">
                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 2 3 14h7l-1 8 10-12h-7l1-8Z"/></svg>
                    </span>
                    <h3 class="font-semibold text-gray-900 mt-3">Analisar currículo</h3>
                    <p class="text-sm text-gray-500 mt-1">Receba nota e recomendações da IA.</p>
                </a>

                <a href="{{ route('job.analyze') }}" wire:navigate
                    class="group bg-white border border-gray-200 rounded-xl p-5 hover:border-accent hover:shadow-sm transition">
                    <span class="w-9 h-9 rounded-lg bg-accent/10 text-accent-dark flex items-center justify-center">
                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 11l3 3L22 4M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    </span>
                    <h3 class="font-semibold text-gray-900 mt-3">Analisar uma vaga</h3>
                    <p class="text-sm text-gray-500 mt-1">Compare seu currículo com uma vaga.</p>
                </a>
            </div>

            {{-- Nota + atividade recente --}}
            <div class="grid grid-cols-1 lg:grid-cols-[1.1fr_1fr] gap-6">

                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Nota do currículo</h3>

                    @if (! $resume)
                        <div class="text-center py-8">
                            <p class="text-sm text-gray-500">Você ainda não criou um currículo.</p>
                            <a href="{{ route('resume.builder') }}" wire:navigate class="inline-flex mt-3 text-sm font-semibold text-accent-dark hover:underline">Criar agora →</a>
                        </div>
                    @elseif (! $latestAnalysis)
                        <div class="text-center py-8">
                            <p class="text-sm text-gray-500">Seu currículo ainda não foi analisado.</p>
                            <a href="{{ route('resume.analyze', $resume) }}" wire:navigate class="inline-flex mt-3 text-sm font-semibold text-accent-dark hover:underline">Analisar agora →</a>
                        </div>
                    @else
                        <div class="flex items-center gap-6 flex-wrap">
                            <div class="relative w-28 h-28 shrink-0">
                                <svg viewBox="0 0 36 36" class="w-28 h-28 -rotate-90">
                                    <circle cx="18" cy="18" r="16" fill="none" stroke="#E9EEF5" stroke-width="3"></circle>
                                    <circle cx="18" cy="18" r="16" fill="none" stroke="#16A34A" stroke-width="3" stroke-linecap="round"
                                        stroke-dasharray="{{ round($latestAnalysis->score * 100.53 / 100, 1) }} 100.53" />
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    <span class="text-2xl font-bold text-gray-900">{{ $latestAnalysis->score }}</span>
                                    <span class="text-[10px] text-gray-400">de 100</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-[200px] space-y-2">
                                @foreach (['structure' => 'Estrutura', 'clarity' => 'Clareza', 'ats' => 'ATS'] as $key => $label)
                                    <div class="grid grid-cols-[80px_1fr_28px] items-center gap-2 text-xs">
                                        <span class="text-gray-500">{{ $label }}</span>
                                        <div class="h-1.5 rounded-full bg-gray-100 overflow-hidden">
                                            <div class="h-full bg-brand rounded-full" style="width: {{ $latestAnalysis->categories[$key] ?? 0 }}%"></div>
                                        </div>
                                        <span class="text-gray-700 font-medium text-right">{{ $latestAnalysis->categories[$key] ?? '—' }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <a href="{{ route('resume.analyze', $resume) }}" wire:navigate class="inline-flex mt-5 text-sm font-semibold text-accent-dark hover:underline">Ver análise completa →</a>
                    @endif
                </div>

                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Histórico recente</h3>

                    @if ($activity->isEmpty())
                        <p class="text-sm text-gray-500 text-center py-8">Nenhuma atividade ainda.</p>
                    @else
                        <ul class="space-y-4">
                            @foreach ($activity as $item)
                                <li class="flex items-start gap-3">
                                    <span class="w-7 h-7 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center shrink-0 mt-0.5">
                                        @switch($item['icon'])
                                            @case('analysis')
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 2 3 14h7l-1 8 10-12h-7l1-8Z"/></svg>
                                                @break
                                            @case('match')
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 11l3 3L22 4M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                                                @break
                                            @case('customize')
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="3"/><path stroke-linecap="round" d="M12 2v3M12 19v3M4.2 4.2l2.1 2.1M17.7 17.7l2.1 2.1M2 12h3M19 12h3M4.2 19.8l2.1-2.1M17.7 6.3l2.1-2.1"/></svg>
                                                @break
                                            @default
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4h12l4 4v12H4z"/><path d="M8 4v6h8V4"/></svg>
                                        @endswitch
                                    </span>
                                    <div>
                                        <p class="text-sm text-gray-800">{{ $item['label'] }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $item['at']->diffForHumans() }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
