<div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    <div class="flex items-center justify-between mb-6 gap-4 flex-wrap">
        <div>
            <p class="text-xs font-bold uppercase tracking-wide text-accent-dark">Exportar</p>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Escolha o template e gere o PDF</h1>
        </div>
        <a href="{{ route('resume.pdf.download', ['resume' => $resume, 'template' => $selectedTemplate]) }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-accent hover:bg-accent-dark rounded-lg px-4 py-2.5 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0-4-4m4 4 4-4M4 21h16"/></svg>
            Baixar PDF
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-6">
        @foreach ($templates as $template)
            <button type="button" wire:click="selectTemplate('{{ $template->value }}')"
                class="rounded-lg border-2 overflow-hidden text-left transition
                    {{ $selectedTemplate === $template->value ? 'border-accent ring-2 ring-accent/30' : 'border-gray-200 hover:border-gray-300' }}">
                <div class="aspect-[3/4] bg-gray-50 flex items-center justify-center text-gray-300">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M9 8h6M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/></svg>
                </div>
                <p class="text-xs font-semibold text-center py-2 border-t border-gray-200">{{ $template->label() }}</p>
            </button>
        @endforeach
    </div>

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden" style="height: 80vh;">
        <iframe src="{{ route('resume.pdf.download', ['resume' => $resume, 'template' => $selectedTemplate, 'inline' => 1]) }}"
            wire:key="preview-{{ $selectedTemplate }}" class="w-full h-full" title="Pré-visualização do currículo"></iframe>
    </div>

</div>
