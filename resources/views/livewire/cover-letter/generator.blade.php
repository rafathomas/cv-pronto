<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    <p class="text-xs font-bold uppercase tracking-wide text-accent-dark">Carta de apresentação</p>
    <h1 class="text-2xl font-bold text-gray-900 mt-1">Sua carta para esta vaga</h1>
    <p class="text-sm text-gray-500 mt-1">Gerada a partir do seu currículo e da descrição da vaga.</p>

    @error('ai')
        <div class="mt-4 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3">
            {{ $message }}
        </div>
    @enderror

    <div class="grid grid-cols-1 sm:grid-cols-[280px_1fr] gap-5 mt-6">
        <div class="bg-white border border-gray-200 rounded-xl p-5 space-y-4 h-fit">
            <h3 class="font-semibold text-gray-900 text-sm">Contexto usado pela IA</h3>
            <div>
                <x-input-label for="company" value="Empresa" />
                <x-text-input id="company" wire:model="company" class="mt-1 block w-full" />
                <x-input-error :messages="$errors->get('company')" class="mt-1" />
            </div>
            <div>
                <x-input-label for="tone" value="Tom" />
                <x-text-input id="tone" wire:model="tone" class="mt-1 block w-full" />
            </div>
            <button type="button" wire:click="generate" wire:loading.attr="disabled" wire:target="generate"
                class="inline-flex items-center justify-center gap-2 w-full text-sm font-semibold text-white bg-brand hover:bg-brand-dark disabled:opacity-60 rounded-lg px-4 py-2.5 transition">
                <svg wire:loading.remove wire:target="generate" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 2 3 14h7l-1 8 10-12h-7l1-8Z"/></svg>
                <svg wire:loading wire:target="generate" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                <span wire:loading.remove wire:target="generate">{{ $letter ? 'Gerar novamente' : 'Gerar carta' }}</span>
                <span wire:loading wire:target="generate">Gerando...</span>
            </button>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-6">
            @if (! $letter)
                <p class="text-gray-500 text-sm text-center py-10">Preencha a empresa e clique em "Gerar carta" para começar.</p>
            @else
                <textarea
                    x-data="{ content: @entangle('letter.content') }"
                    x-model="content"
                    x-on:blur="$wire.updateContent(content)"
                    rows="16"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent text-sm leading-relaxed"
                ></textarea>

                <div class="flex justify-end gap-3 mt-4">
                    <button type="button"
                        x-data
                        x-on:click="navigator.clipboard.writeText(@js($letter->content)); $dispatch('notify', 'Carta copiada!')"
                        class="text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg px-4 py-2.5 transition">
                        Copiar
                    </button>
                    <a href="{{ route('resume.templates', $resume) }}" wire:navigate
                        class="text-sm font-semibold text-white bg-accent hover:bg-accent-dark rounded-lg px-4 py-2.5 transition">
                        Ir para templates
                    </a>
                </div>
            @endif
        </div>
    </div>

</div>
