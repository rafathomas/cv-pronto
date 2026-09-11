<div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    <div class="flex items-center justify-between mb-6 gap-4 flex-wrap">
        <div>
            <p class="text-xs font-bold uppercase tracking-wide text-accent-dark">Exportar</p>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Escolha o template e a cor</h1>
        </div>
        <a href="{{ route('resume.pdf.download', ['resume' => $resume, 'template' => $selectedTemplate]) }}"
            class="inline-flex items-center gap-2 text-sm font-semibold text-white bg-accent hover:bg-accent-dark rounded-lg px-4 py-2.5 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0-4-4m4 4 4-4M4 21h16"/></svg>
            Baixar PDF
        </a>
    </div>

    {{-- Seletor de cor de destaque --}}
    <div class="flex items-center gap-3 mb-6 flex-wrap">
        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Cor de destaque</span>
        <div class="flex items-center gap-2">
            @foreach ($accentColors as $name => $hex)
                <button type="button" wire:click="selectColor('{{ $hex }}')" title="{{ $name }}" aria-label="Cor {{ $name }}"
                    class="w-7 h-7 rounded-full border-2 transition flex items-center justify-center {{ $selectedColor === $hex ? 'border-gray-800 scale-110' : 'border-transparent hover:scale-105' }}"
                    style="background-color: {{ $hex }};">
                    @if ($selectedColor === $hex)
                        <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5"/></svg>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    @error('template')
        <x-alert class="flex items-center justify-between gap-3 mb-4">
            <span>{{ $message }}</span>
            <a href="{{ route('billing.plans') }}" class="shrink-0 font-semibold text-amber-900 hover:underline">Ver planos</a>
        </x-alert>
    @enderror

    {{-- Miniaturas dos templates --}}
    @php $thumbPhoto = $resume->hasPhoto() ? $resume->photoDataUri() : null; @endphp
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3 mb-6">
        @foreach ($templates as $template)
            @php $locked = $template->isPremium() && ! $hasPremiumTemplates; @endphp
            <button type="button" wire:click="selectTemplate('{{ $template->value }}')"
                class="relative rounded-lg border-2 overflow-hidden text-left transition bg-white
                    {{ $selectedTemplate === $template->value ? 'border-accent ring-2 ring-accent/30' : 'border-gray-200 hover:border-gray-300' }}
                    {{ $locked ? 'opacity-60' : '' }}">

                @if ($locked)
                    <span class="absolute top-1.5 right-1.5 z-10 inline-flex items-center gap-1 text-[10px] font-semibold text-white bg-gray-900/80 rounded-full px-2 py-0.5">
                        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2ZM7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        Premium
                    </span>
                @endif

                <div class="aspect-[3/4] p-2 overflow-hidden" style="background: #f8fafc;">
                    @switch($template->value)
                        @case('classico')
                            <div class="h-full bg-white p-2 text-[4px] leading-none" style="border-bottom: 2px solid #1E3A5F;">
                                <div class="flex items-start justify-between mb-1">
                                    <div class="flex-1">
                                        <div class="h-1.5 w-4/5 rounded-sm mb-1" style="background:#1E3A5F"></div>
                                        <div class="h-0.5 w-3/5 rounded-sm bg-gray-300"></div>
                                    </div>
                                    <div class="w-4 h-4 rounded-full shrink-0 ml-1 bg-gray-200 bg-cover bg-center" @if($thumbPhoto) style="background-image:url('{{ $thumbPhoto }}')" @endif></div>
                                </div>
                                <div class="mb-1"></div>
                                @for ($i = 0; $i < 3; $i++)
                                    <div class="flex items-center gap-0.5 mb-1">
                                        <span class="w-1 h-1 shrink-0" style="background: {{ $selectedColor }}"></span>
                                        <div class="h-1 w-2/5 rounded-sm" style="background:#1E3A5F"></div>
                                    </div>
                                    <div class="h-0.5 w-full rounded-sm bg-gray-200 mb-0.5"></div>
                                    <div class="h-0.5 w-5/6 rounded-sm bg-gray-200 mb-2"></div>
                                @endfor
                            </div>
                            @break

                        @case('moderno')
                            <div class="h-full bg-white flex overflow-hidden text-[4px] leading-none">
                                <div class="w-2/5 h-full p-1.5" style="background:#1E3A5F">
                                    <div class="w-3 h-3 rounded-full mb-1 bg-cover bg-center" style="background-color:{{ $selectedColor }}; @if($thumbPhoto) background-image:url('{{ $thumbPhoto }}') @endif"></div>
                                    <div class="h-1 w-4/5 rounded-sm bg-white mb-2"></div>
                                    @for ($i = 0; $i < 3; $i++)
                                        <div class="h-0.5 w-full rounded-sm mb-0.5" style="background:rgba(255,255,255,0.5)"></div>
                                        <div class="h-0.5 w-3/5 rounded-sm mb-1.5" style="background:{{ $selectedColor }}"></div>
                                    @endfor
                                </div>
                                <div class="w-3/5 h-full p-1.5">
                                    @for ($i = 0; $i < 3; $i++)
                                        <div class="h-1 w-2/5 rounded-sm mb-1" style="background:#1E3A5F; border-bottom:1px solid {{ $selectedColor }}"></div>
                                        <div class="h-0.5 w-full rounded-sm bg-gray-200 mb-0.5"></div>
                                        <div class="h-0.5 w-full rounded-sm bg-gray-200 mb-2"></div>
                                    @endfor
                                </div>
                            </div>
                            @break

                        @case('minimalista')
                            <div class="h-full bg-white p-2 text-[4px] leading-none">
                                <div class="h-1.5 w-2/5 rounded-sm mb-2" style="background:#222; border-bottom:1.5px solid {{ $selectedColor }}"></div>
                                @for ($i = 0; $i < 3; $i++)
                                    <div class="h-0.5 w-1/4 rounded-sm bg-gray-300 mb-1"></div>
                                    <div class="h-0.5 w-full rounded-sm bg-gray-200 mb-0.5"></div>
                                    <div class="h-0.5 w-4/5 rounded-sm bg-gray-200 mb-2.5"></div>
                                @endfor
                            </div>
                            @break

                        @case('executivo')
                            <div class="h-full bg-white overflow-hidden text-[4px] leading-none">
                                <div class="p-1.5 flex items-center justify-between" style="background:#1E3A5F; border-bottom:2px solid {{ $selectedColor }}">
                                    <div class="h-1.5 w-3/5 rounded-sm bg-white"></div>
                                    <div class="w-4 h-4 rounded-full shrink-0 bg-cover bg-center" style="background-color:rgba(255,255,255,0.3); @if($thumbPhoto) background-image:url('{{ $thumbPhoto }}') @endif"></div>
                                </div>
                                <div class="p-1.5">
                                    @for ($i = 0; $i < 3; $i++)
                                        <div class="h-1 w-2/5 rounded-sm mb-1" style="background:#1E3A5F"></div>
                                        <div class="h-0.5 w-full rounded-sm bg-gray-200 mb-0.5"></div>
                                        <div class="h-0.5 w-full rounded-sm bg-gray-200 mb-2"></div>
                                    @endfor
                                </div>
                            </div>
                            @break

                        @case('tecnologia')
                            <div class="h-full overflow-hidden text-[4px] leading-none" style="background:#f8fafc">
                                <div class="p-1.5 m-1 rounded-sm flex items-center justify-between" style="background:#0f172a">
                                    <div class="h-1.5 w-3/5 rounded-sm" style="background:{{ $selectedColor }}"></div>
                                    <div class="w-3.5 h-3.5 rounded-full shrink-0 bg-cover bg-center" style="border:1px solid {{ $selectedColor }}; @if($thumbPhoto) background-image:url('{{ $thumbPhoto }}') @endif"></div>
                                </div>
                                <div class="p-1.5">
                                    @for ($i = 0; $i < 3; $i++)
                                        <div class="flex items-center gap-0.5 mb-1">
                                            <span class="w-1 h-1 shrink-0" style="background:{{ $selectedColor }}"></span>
                                            <div class="h-1 w-2/5 rounded-sm bg-gray-700"></div>
                                        </div>
                                        <div class="h-0.5 w-full rounded-sm bg-gray-300 mb-0.5"></div>
                                        <div class="h-0.5 w-full rounded-sm bg-gray-300 mb-2"></div>
                                    @endfor
                                </div>
                            </div>
                            @break

                        @case('elegante')
                            <div class="h-full bg-white flex overflow-hidden text-[4px] leading-none">
                                <div class="w-1 h-full shrink-0" style="background:{{ $selectedColor }}"></div>
                                <div class="flex-1 p-1.5">
                                    <div class="flex items-start justify-between mb-1">
                                        <div class="flex-1">
                                            <div class="h-1.5 w-3/5 rounded-sm mb-1 bg-gray-800"></div>
                                            <div class="h-0.5 w-2/5 rounded-sm bg-gray-300"></div>
                                        </div>
                                        <div class="w-4 h-4 rounded-full shrink-0 ml-1 bg-gray-200 bg-cover bg-center" @if($thumbPhoto) style="background-image:url('{{ $thumbPhoto }}')" @endif></div>
                                    </div>
                                    @for ($i = 0; $i < 3; $i++)
                                        <div class="h-1 w-2/5 rounded-sm mb-1 mt-2" style="color:{{ $selectedColor }}; background:{{ $selectedColor }}"></div>
                                        <div class="h-0.5 w-full rounded-sm bg-gray-200 mb-0.5"></div>
                                        <div class="h-0.5 w-5/6 rounded-sm bg-gray-200"></div>
                                    @endfor
                                </div>
                            </div>
                            @break

                        @case('criativo')
                            <div class="h-full bg-white overflow-hidden text-[4px] leading-none">
                                <div class="p-2 text-center" style="background:{{ $selectedColor }}">
                                    <div class="w-5 h-5 rounded-full mx-auto mb-1 bg-cover bg-center" style="background-color:rgba(255,255,255,0.3); @if($thumbPhoto) background-image:url('{{ $thumbPhoto }}') @endif"></div>
                                    <div class="h-1 w-3/5 mx-auto rounded-sm bg-white"></div>
                                </div>
                                <div class="p-1.5">
                                    @for ($i = 0; $i < 3; $i++)
                                        <div class="h-1 w-2/5 rounded-sm mb-1" style="border-left:2px solid {{ $selectedColor }}; padding-left:2px;">&nbsp;</div>
                                        <div class="h-0.5 w-full rounded-sm bg-gray-200 mb-0.5"></div>
                                        <div class="h-0.5 w-full rounded-sm bg-gray-200 mb-2"></div>
                                    @endfor
                                </div>
                            </div>
                            @break
                    @endswitch
                </div>

                <p class="text-xs font-semibold text-center py-2 border-t border-gray-200">{{ $template->label() }}</p>
            </button>
        @endforeach
    </div>

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden" style="height: 80vh;">
        <iframe src="{{ route('resume.pdf.download', ['resume' => $resume, 'template' => $selectedTemplate, 'inline' => 1]) }}"
            wire:key="preview-{{ $selectedTemplate }}-{{ $selectedColor }}" class="w-full h-full" title="Pré-visualização do currículo"></iframe>
    </div>

</div>
