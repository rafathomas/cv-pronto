<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    <p class="text-xs font-bold uppercase tracking-wide text-accent-dark">Currículo personalizado</p>
    <h1 class="text-2xl font-bold text-gray-900 mt-1">Adaptar currículo para esta vaga</h1>
    <p class="text-sm text-gray-500 mt-1">A IA reorganiza e melhora a apresentação do que você já tem — sem inventar experiência, habilidades ou empresas.</p>

    @error('ai')
        <div class="mt-4 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3">
            {{ $message }}
        </div>
    @enderror

    @if (! $customized)
        <div class="bg-white border border-dashed border-gray-300 rounded-xl p-10 text-center mt-6">
            <p class="text-gray-500">Ainda não existe uma versão adaptada deste currículo para esta vaga.</p>
            <button type="button" wire:click="generate" wire:loading.attr="disabled" wire:target="generate"
                class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-accent hover:bg-accent-dark disabled:opacity-60 rounded-lg px-4 py-2.5 mt-4 transition">
                <svg wire:loading.remove wire:target="generate" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="3"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 2v3M12 19v3M4.2 4.2l2.1 2.1M17.7 17.7l2.1 2.1M2 12h3M19 12h3M4.2 19.8l2.1-2.1M17.7 6.3l2.1-2.1"/></svg>
                <svg wire:loading wire:target="generate" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                <span wire:loading.remove wire:target="generate">Gerar versão adaptada</span>
                <span wire:loading wire:target="generate">Gerando...</span>
            </button>
        </div>
    @else
        <div class="space-y-5 mt-6">
            @if ($customized->summary)
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <h3 class="font-semibold text-gray-900 mb-2 text-sm">Resumo adaptado</h3>
                    <p class="text-sm text-gray-700">{{ $customized->summary }}</p>
                </div>
            @endif

            @if (count($customized->experience_descriptions))
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <h3 class="font-semibold text-gray-900 mb-3 text-sm">Experiências destacadas</h3>
                    <div class="space-y-3">
                        @foreach ($resume->experiences as $experience)
                            @if (isset($customized->experience_descriptions[$experience->id]))
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $experience->position }} — {{ $experience->company }}</p>
                                    <p class="text-sm text-gray-600 mt-1">{{ $customized->experience_descriptions[$experience->id] }}</p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            @if (count($customized->highlighted_skills))
                <div class="bg-white border border-gray-200 rounded-xl p-5">
                    <h3 class="font-semibold text-gray-900 mb-3 text-sm">Habilidades priorizadas para esta vaga</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($customized->highlighted_skills as $skill)
                            <span class="text-xs font-semibold bg-green-50 text-accent-dark rounded-md px-2.5 py-1">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (count($customized->notes))
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
                    <h3 class="font-semibold text-blue-900 mb-2 text-sm">Observações</h3>
                    <ul class="list-disc list-inside space-y-1 text-sm text-blue-900">
                        @foreach ($customized->notes as $note)
                            <li>{{ $note }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex justify-end gap-3">
                <button type="button" wire:click="generate" wire:loading.attr="disabled" wire:target="generate"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 disabled:opacity-60 rounded-lg px-4 py-2.5 transition">
                    Gerar novamente
                </button>
                <a href="{{ route('resume.cover-letter', $jobDescription) }}" wire:navigate
                    class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-brand hover:bg-brand-dark rounded-lg px-4 py-2.5 transition">
                    Gerar carta de apresentação
                </a>
            </div>
        </div>
    @endif

</div>
