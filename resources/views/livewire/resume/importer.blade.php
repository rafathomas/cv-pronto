<div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    <p class="text-xs font-bold uppercase tracking-wide text-accent-dark">Importar currículo</p>
    <h1 class="text-2xl font-bold text-gray-900 mt-1">Já tem um currículo pronto?</h1>
    <p class="text-sm text-gray-500 mt-1">Envie seu arquivo e a IA preenche os campos automaticamente. Nada é inventado — o que não estiver no arquivo fica em branco para você completar. Você poderá revisar tudo antes de salvar.</p>

    @error('ai')
        <div class="mt-4 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3">{{ $message }}</div>
    @enderror
    @error('file')
        <div class="mt-4 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3">{{ $message }}</div>
    @enderror

    @if (! $extracted)
        <div class="bg-white border-2 border-dashed border-gray-300 rounded-xl p-10 text-center mt-6" x-data>
            <input type="file" wire:model="file" id="resume-file" class="hidden" accept=".pdf,.docx">
            <p class="text-gray-600 font-medium">Arraste seu arquivo aqui ou clique para selecionar</p>
            <p class="text-xs text-gray-400 mt-1">PDF ou DOCX · até 5MB</p>

            @if ($file)
                <p class="text-sm text-gray-700 mt-4 font-medium">{{ $file->getClientOriginalName() }}</p>
            @endif

            <div class="flex items-center justify-center gap-3 mt-5">
                <label for="resume-file" class="cursor-pointer text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg px-4 py-2.5 transition">
                    Selecionar arquivo
                </label>
                <button type="button" wire:click="upload" wire:loading.attr="disabled" wire:target="upload,file"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-accent hover:bg-accent-dark disabled:opacity-60 rounded-lg px-4 py-2.5 transition">
                    <svg wire:loading wire:target="upload" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                    <span wire:loading.remove wire:target="upload">Processar arquivo</span>
                    <span wire:loading wire:target="upload">Processando...</span>
                </button>
            </div>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('resume.builder') }}" wire:navigate class="text-sm font-semibold text-accent-dark">Prefere criar manualmente? →</a>
        </div>
    @else
        <div class="bg-white border border-gray-200 rounded-xl p-6 mt-6 space-y-4">
            <h3 class="font-semibold text-gray-900">Prévia dos dados extraídos</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                <p><span class="text-gray-400">Nome:</span> {{ $extracted['full_name'] ?: '—' }}</p>
                <p><span class="text-gray-400">E-mail:</span> {{ $extracted['email'] ?: '—' }}</p>
                <p><span class="text-gray-400">Telefone:</span> {{ $extracted['phone'] ?: '—' }}</p>
                <p><span class="text-gray-400">Cidade/UF:</span> {{ $extracted['city'] ? $extracted['city'].'/'.$extracted['state'] : '—' }}</p>
            </div>

            <p class="text-sm"><span class="text-gray-400">Resumo:</span> {{ $extracted['professional_summary'] ?: 'Não encontrado' }}</p>

            <p class="text-sm text-gray-600">
                {{ count($extracted['experiences']) }} experiência(s) ·
                {{ count($extracted['education']) }} formação(ões) ·
                {{ count($extracted['skills']) }} habilidade(s) ·
                {{ count($extracted['languages']) }} idioma(s)
            </p>

            <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 text-sm text-blue-900">
                Você poderá revisar e editar cada campo no próximo passo, antes de salvar definitivamente.
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" wire:click="$set('extracted', null)" class="text-sm font-semibold text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg px-4 py-2.5 transition">
                    Enviar outro arquivo
                </button>
                <button type="button" wire:click="confirm" class="text-sm font-semibold text-white bg-accent hover:bg-accent-dark rounded-lg px-4 py-2.5 transition">
                    Usar estes dados e continuar
                </button>
            </div>
        </div>
    @endif

</div>
