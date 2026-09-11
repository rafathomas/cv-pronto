<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    <p class="text-xs font-bold uppercase tracking-wide text-accent-dark">/analisar-vaga</p>
    <h1 class="text-2xl font-bold text-gray-900 mt-1">Compare seu currículo com uma vaga</h1>
    <p class="text-sm text-gray-500 mt-1">Cole a descrição da vaga abaixo. A IA compara com seu currículo atual e mostra o quanto vocês combinam.</p>

    @error('ai')
        <x-alert class="mt-4">{{ $message }}</x-alert>
    @enderror

    <div class="bg-white border border-gray-200 rounded-xl p-6 mt-6">
        <x-input-label for="jobDescription" value="Descrição da vaga" />
        <textarea id="jobDescription" wire:model="jobDescription" rows="7"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent text-sm"
            placeholder="Cole aqui a descrição completa da vaga..."></textarea>
        <x-input-error :messages="$errors->get('jobDescription')" class="mt-1" />

        <div class="flex justify-end mt-4">
            <button type="button" wire:click="compare" wire:loading.attr="disabled" wire:target="compare"
                class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-brand hover:bg-brand-dark disabled:opacity-60 rounded-lg px-4 py-2.5 transition">
                <svg wire:loading.remove wire:target="compare" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 11l3 3L22 4M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                <svg wire:loading wire:target="compare" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                <span wire:loading.remove wire:target="compare">Comparar com meu currículo</span>
                <span wire:loading wire:target="compare">Comparando...</span>
            </button>
        </div>
    </div>

    @if ($result)
        <div class="grid grid-cols-1 sm:grid-cols-[auto_1fr] gap-8 items-center my-8 bg-white border border-gray-200 rounded-xl p-6">
            <div class="mx-auto sm:mx-0 relative w-36 h-36">
                <svg viewBox="0 0 36 36" class="w-36 h-36 -rotate-90">
                    <circle cx="18" cy="18" r="16" fill="none" stroke="#E9EEF5" stroke-width="3"></circle>
                    <circle cx="18" cy="18" r="16" fill="none" stroke="#1E3A5F" stroke-width="3" stroke-linecap="round"
                        stroke-dasharray="{{ round($result->match_score * 100.53 / 100, 1) }} 100.53" />
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-3xl font-bold text-gray-900">{{ $result->match_score }}%</span>
                    <span class="text-xs text-gray-500">compatibilidade</span>
                </div>
            </div>
            <div>
                <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-full
                    {{ $result->match_score >= 70 ? 'bg-green-50 text-accent-dark' : ($result->match_score >= 40 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700') }}">
                    {{ $result->match_score >= 70 ? 'Boa aderência à vaga' : ($result->match_score >= 40 ? 'Aderência parcial' : 'Baixa aderência') }}
                </span>
                <p class="text-sm text-gray-700 mt-3">Comparado com <strong>{{ $resume->title }}</strong>.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <h3 class="font-semibold text-gray-900 mb-3 text-sm">Competências encontradas</h3>
                <div class="flex flex-wrap gap-2 mb-4">
                    @forelse ($result->matched_skills as $skill)
                        <span class="text-xs font-semibold bg-green-50 text-accent-dark rounded-md px-2.5 py-1">✓ {{ $skill }}</span>
                    @empty
                        <span class="text-xs text-gray-400">Nenhuma correspondência direta encontrada.</span>
                    @endforelse
                </div>

                <h3 class="font-semibold text-gray-900 mb-3 text-sm">Parcialmente compatíveis</h3>
                <div class="flex flex-wrap gap-2 mb-4">
                    @forelse ($result->partial_skills as $skill)
                        <span class="text-xs font-semibold bg-amber-50 text-amber-700 rounded-md px-2.5 py-1">~ {{ $skill }}</span>
                    @empty
                        <span class="text-xs text-gray-400">Nenhuma.</span>
                    @endforelse
                </div>

                <h3 class="font-semibold text-gray-900 mb-3 text-sm">Ausentes no currículo</h3>
                <div class="flex flex-wrap gap-2">
                    @forelse ($result->missing_skills as $skill)
                        <span class="text-xs font-semibold bg-gray-100 text-gray-500 line-through rounded-md px-2.5 py-1">{{ $skill }}</span>
                    @empty
                        <span class="text-xs text-gray-400">Nenhuma competência importante faltando.</span>
                    @endforelse
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <h3 class="font-semibold text-gray-900 mb-3 text-sm">Recomendações</h3>
                <ul class="space-y-2 mb-5">
                    @foreach ($result->recommendations as $item)
                        <li class="flex gap-2 text-sm text-gray-700">
                            <svg class="w-4 h-4 text-accent-dark shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5"/></svg>
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('resume.customize', $result->jobDescription) }}" wire:navigate
                    class="inline-flex items-center justify-center gap-2 w-full text-sm font-semibold text-white bg-accent hover:bg-accent-dark rounded-lg px-4 py-2.5 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="3"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 2v3M12 19v3M4.2 4.2l2.1 2.1M17.7 17.7l2.1 2.1M2 12h3M19 12h3M4.2 19.8l2.1-2.1M17.7 6.3l2.1-2.1"/></svg>
                    Adaptar currículo para esta vaga
                </a>
            </div>
        </div>
    @endif

</div>
