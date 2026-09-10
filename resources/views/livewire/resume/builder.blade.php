<div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8 pb-28">

    <div class="flex items-center justify-between mb-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Criar currículo</h1>
            <p class="text-sm text-gray-500 mt-1">Passo {{ $currentStepIndex + 1 }} de {{ count($steps) }} · as alterações são salvas automaticamente.</p>
        </div>
        <div class="flex items-center gap-3">
            <span x-data="{ show: false }"
                  x-on:resume-saved.window="show = true; setTimeout(() => show = false, 2000)"
                  x-show="show" x-cloak
                  class="hidden sm:inline-flex items-center gap-1.5 text-xs font-semibold text-accent-dark bg-green-50 border border-green-200 rounded-full px-3 py-1.5">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
                Salvo
            </span>
            <a href="{{ route('resume.templates', $resume) }}" wire:navigate
                class="text-sm font-semibold text-gray-500 hover:text-gray-800 transition">
                Ver como PDF →
            </a>
        </div>
    </div>

    {{-- Indicador de progresso --}}
    <div class="mb-8">
        <div class="flex items-center">
            @foreach ($steps as $stepKey => $label)
                @php $index = $loop->index; @endphp
                <button type="button" wire:click="goToStep('{{ $stepKey }}')" class="flex flex-col items-center gap-1.5 group" style="width: {{ 100 / count($steps) }}%">
                    <span @class([
                        'w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-2 transition shrink-0',
                        'bg-accent border-accent text-white' => $index < $currentStepIndex,
                        'bg-white border-accent text-accent-dark' => $index === $currentStepIndex,
                        'bg-white border-gray-300 text-gray-400 group-hover:border-gray-400' => $index > $currentStepIndex,
                    ])>
                        @if ($index < $currentStepIndex)
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5"/></svg>
                        @else
                            {{ $index + 1 }}
                        @endif
                    </span>
                    <span @class([
                        'text-[11px] font-medium text-center leading-tight hidden sm:block',
                        'text-gray-900' => $index === $currentStepIndex,
                        'text-gray-400' => $index !== $currentStepIndex,
                    ])>{{ $label }}</span>
                </button>
                @if (! $loop->last)
                    <div @class(['h-0.5 flex-1 -mt-5 sm:-mt-5', $index < $currentStepIndex ? 'bg-accent' : 'bg-gray-200'])></div>
                @endif
            @endforeach
        </div>
    </div>

    @error('ai')
        <div class="mb-6 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3">
            {{ $message }}
        </div>
    @enderror

    @if (count($missingInfoSuggestions))
        <div class="mb-6 rounded-lg bg-blue-50 border border-blue-200 text-blue-900 text-sm px-4 py-3">
            <p class="font-semibold mb-1.5">A IA notou que algumas informações podem estar faltando:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($missingInfoSuggestions as $suggestion)
                    <li>{{ $suggestion }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Dados pessoais --}}
    @if ($activeTab === 'dados')
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-1">Vamos começar pelo básico</h2>
            <p class="text-sm text-gray-500 mb-5">Essas informações aparecem no topo do seu currículo.</p>
            <form id="dados-form" wire:submit="continueFromDados" class="bg-white border border-gray-200 rounded-xl p-6 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2">
                        <x-input-label for="fullName" value="Nome completo" />
                        <x-text-input id="fullName" wire:model="fullName" class="mt-1 block w-full" autofocus />
                        <x-input-error :messages="$errors->get('fullName')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="email" value="E-mail" />
                        <x-text-input id="email" type="email" wire:model="email" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="phone" value="Telefone" />
                        <x-text-input id="phone" wire:model="phone" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label for="city" value="Cidade" />
                        <x-text-input id="city" wire:model="city" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label for="state" value="Estado" />
                        <x-text-input id="state" wire:model="state" maxlength="2" class="mt-1 block w-full uppercase" />
                    </div>
                    <div>
                        <x-input-label for="linkedinUrl" value="LinkedIn" />
                        <x-text-input id="linkedinUrl" wire:model="linkedinUrl" placeholder="linkedin.com/in/voce" class="mt-1 block w-full" />
                        <x-input-error :messages="$errors->get('linkedinUrl')" class="mt-1" />
                    </div>
                    <div>
                        <x-input-label for="githubUrl" value="GitHub (opcional)" />
                        <x-text-input id="githubUrl" wire:model="githubUrl" placeholder="github.com/voce" class="mt-1 block w-full" />
                    </div>
                    <div class="sm:col-span-2">
                        <x-input-label for="portfolioUrl" value="Site/portfólio (opcional)" />
                        <x-text-input id="portfolioUrl" wire:model="portfolioUrl" placeholder="seusite.com" class="mt-1 block w-full" />
                    </div>
                </div>
            </form>
        </div>
    @endif

    {{-- Resumo --}}
    @if ($activeTab === 'resumo')
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-1">Resumo profissional</h2>
            <p class="text-sm text-gray-500 mb-5">Duas ou três frases sobre sua área, ferramentas e o tipo de impacto que você gera. Pode deixar em branco e usar a IA depois.</p>
            <form id="resumo-form" wire:submit="continueFromResumo" class="bg-white border border-gray-200 rounded-xl p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <x-input-label for="professionalSummary" value="Resumo" />
                    <button type="button" wire:click="improveSummary" wire:loading.attr="disabled" wire:target="improveSummary"
                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-white bg-brand hover:bg-brand-dark disabled:opacity-60 rounded-full px-3 py-1.5 transition">
                        <svg wire:loading.remove wire:target="improveSummary" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 2 3 14h7l-1 8 10-12h-7l1-8Z"/></svg>
                        <svg wire:loading wire:target="improveSummary" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                        <span wire:loading.remove wire:target="improveSummary">Melhorar com IA</span>
                        <span wire:loading wire:target="improveSummary">Melhorando...</span>
                    </button>
                </div>
                <textarea id="professionalSummary" wire:model="professionalSummary" rows="6"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent text-sm"
                    placeholder="Fale sobre sua área, principais ferramentas e o tipo de impacto que você gera."></textarea>
                <x-input-error :messages="$errors->get('professionalSummary')" class="mt-1" />
            </form>
        </div>
    @endif

    {{-- Experiência --}}
    @if ($activeTab === 'experiencia')
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-1">Experiência profissional</h2>
            <p class="text-sm text-gray-500 mb-5">Adicione seus empregos mais recentes primeiro. Sem experiência ainda? Pode pular esta etapa.</p>
            <div class="space-y-4">
                @foreach ($resume->experiences as $experience)
                    <div class="bg-white border border-gray-200 rounded-xl p-5" wire:key="exp-{{ $experience->id }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $experience->position }} — {{ $experience->company }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    {{ $experience->start_date->format('m/Y') }} –
                                    {{ $experience->is_current ? 'Atual' : ($experience->end_date?->format('m/Y') ?? '—') }}
                                </p>
                                @if ($experience->description)
                                    <p class="text-sm text-gray-600 mt-2">{{ $experience->description }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                <button type="button" wire:click="improveExperience({{ $experience->id }})" wire:loading.attr="disabled" wire:target="improveExperience({{ $experience->id }})"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-white bg-brand hover:bg-brand-dark disabled:opacity-60 rounded-full px-3 py-1.5 transition">
                                    <svg wire:loading.remove wire:target="improveExperience({{ $experience->id }})" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 2 3 14h7l-1 8 10-12h-7l1-8Z"/></svg>
                                    <svg wire:loading wire:target="improveExperience({{ $experience->id }})" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                                    <span wire:loading.remove wire:target="improveExperience({{ $experience->id }})">Melhorar descrição</span>
                                    <span wire:loading wire:target="improveExperience({{ $experience->id }})">Melhorando...</span>
                                </button>
                                <button wire:click="deleteExperience({{ $experience->id }})" wire:confirm="Remover esta experiência?"
                                    class="text-gray-400 hover:text-red-600" aria-label="Remover experiência">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach

                <form wire:submit="addExperience" class="bg-white border border-dashed border-gray-300 rounded-xl p-5 space-y-4">
                    <p class="text-sm font-semibold text-gray-800">Adicionar experiência</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="newCompany" value="Empresa" />
                            <x-text-input id="newCompany" wire:model="newCompany" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('newCompany')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="newPosition" value="Cargo" />
                            <x-text-input id="newPosition" wire:model="newPosition" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('newPosition')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="newStartDate" value="Data inicial" />
                            <x-text-input id="newStartDate" type="date" wire:model="newStartDate" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('newStartDate')" class="mt-1" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                        <div>
                            <x-input-label for="newEndDate" value="Data final" />
                            <x-text-input id="newEndDate" type="date" wire:model="newEndDate" :disabled="$newIsCurrent" class="mt-1 block w-full" />
                        </div>
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" wire:model.live="newIsCurrent" class="rounded border-gray-300 text-accent focus:ring-accent">
                            Emprego atual
                        </label>
                    </div>
                    <div>
                        <x-input-label for="newDescription" value="Descrição" />
                        <textarea id="newDescription" wire:model="newDescription" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent text-sm"></textarea>
                    </div>
                    <div class="flex justify-end">
                        <x-secondary-button type="submit">Adicionar experiência</x-secondary-button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Formação --}}
    @if ($activeTab === 'formacao')
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-1">Formação acadêmica</h2>
            <p class="text-sm text-gray-500 mb-5">Ensino superior, técnico ou em andamento.</p>
            <div class="space-y-4">
                @foreach ($resume->education as $edu)
                    <div class="bg-white border border-gray-200 rounded-xl p-5" wire:key="edu-{{ $edu->id }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $edu->course }} — {{ $edu->institution }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    {{ $edu->degree }}
                                    @if ($edu->start_date || $edu->end_date)
                                        · {{ $edu->start_date?->format('Y') }} – {{ $edu->end_date?->format('Y') ?? 'Atual' }}
                                    @endif
                                </p>
                            </div>
                            <button wire:click="deleteEducation({{ $edu->id }})" wire:confirm="Remover esta formação?"
                                class="text-gray-400 hover:text-red-600" aria-label="Remover formação">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
                            </button>
                        </div>
                    </div>
                @endforeach

                <form wire:submit="addEducation" class="bg-white border border-dashed border-gray-300 rounded-xl p-5 space-y-4">
                    <p class="text-sm font-semibold text-gray-800">Adicionar formação</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="newInstitution" value="Instituição" />
                            <x-text-input id="newInstitution" wire:model="newInstitution" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('newInstitution')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="newCourse" value="Curso" />
                            <x-text-input id="newCourse" wire:model="newCourse" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('newCourse')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="newDegree" value="Grau" />
                            <x-text-input id="newDegree" wire:model="newDegree" placeholder="Bacharelado, Técnico..." class="mt-1 block w-full" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="newEduStartDate" value="Data inicial" />
                            <x-text-input id="newEduStartDate" type="date" wire:model="newEduStartDate" class="mt-1 block w-full" />
                        </div>
                        <div>
                            <x-input-label for="newEduEndDate" value="Data final" />
                            <x-text-input id="newEduEndDate" type="date" wire:model="newEduEndDate" class="mt-1 block w-full" />
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <x-secondary-button type="submit">Adicionar formação</x-secondary-button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Cursos --}}
    @if ($activeTab === 'cursos')
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-1">Cursos livres</h2>
            <p class="text-sm text-gray-500 mb-5">Cursos, workshops ou certificações relevantes. Opcional.</p>
            <div class="space-y-4">
                @foreach ($resume->courses as $course)
                    <div class="bg-white border border-gray-200 rounded-xl p-5 flex justify-between items-center" wire:key="course-{{ $course->id }}">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $course->name }}</p>
                            @if ($course->institution)
                                <p class="text-xs text-gray-500 mt-0.5">{{ $course->institution }}</p>
                            @endif
                        </div>
                        <button wire:click="deleteCourse({{ $course->id }})" wire:confirm="Remover este curso?"
                            class="text-gray-400 hover:text-red-600" aria-label="Remover curso">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
                        </button>
                    </div>
                @endforeach

                <form wire:submit="addCourse" class="bg-white border border-dashed border-gray-300 rounded-xl p-5 space-y-4">
                    <p class="text-sm font-semibold text-gray-800">Adicionar curso</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="newCourseName" value="Nome do curso" />
                            <x-text-input id="newCourseName" wire:model="newCourseName" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('newCourseName')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="newCourseInstitution" value="Instituição" />
                            <x-text-input id="newCourseInstitution" wire:model="newCourseInstitution" class="mt-1 block w-full" />
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <x-secondary-button type="submit">Adicionar curso</x-secondary-button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Habilidades --}}
    @if ($activeTab === 'habilidades')
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-1">Habilidades</h2>
            <p class="text-sm text-gray-500 mb-5">Ferramentas, tecnologias ou competências que você domina.</p>
            <div class="bg-white border border-gray-200 rounded-xl p-6 space-y-4">
                <div class="flex flex-wrap gap-2">
                    @forelse ($resume->skills as $skill)
                        <span wire:key="skill-{{ $skill->id }}" class="inline-flex items-center gap-2 bg-gray-100 border border-gray-200 rounded-full px-3 py-1.5 text-sm font-medium text-gray-800">
                            {{ $skill->name }}
                            <button wire:click="removeSkill({{ $skill->id }})" class="text-gray-400 hover:text-red-600" aria-label="Remover {{ $skill->name }}">×</button>
                        </span>
                    @empty
                        <p class="text-sm text-gray-400">Nenhuma habilidade adicionada ainda.</p>
                    @endforelse
                </div>
                <form wire:submit="addSkill" class="flex gap-2">
                    <x-text-input wire:model="newSkillName" placeholder="Ex.: Excel avançado" class="flex-1" />
                    <x-secondary-button type="submit">Adicionar</x-secondary-button>
                </form>
                <x-input-error :messages="$errors->get('newSkillName')" class="mt-1" />
            </div>
        </div>
    @endif

    {{-- Idiomas --}}
    @if ($activeTab === 'idiomas')
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-1">Idiomas</h2>
            <p class="text-sm text-gray-500 mb-5">Último passo! Adicione os idiomas que você fala.</p>
            <div class="space-y-4">
                @foreach ($resume->languages as $language)
                    <div class="bg-white border border-gray-200 rounded-xl p-5 flex justify-between items-center" wire:key="lang-{{ $language->id }}">
                        <p class="font-semibold text-gray-900">{{ $language->name }} <span class="text-gray-500 font-normal">— {{ $language->level->label() }}</span></p>
                        <button wire:click="removeLanguage({{ $language->id }})" class="text-gray-400 hover:text-red-600" aria-label="Remover idioma">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
                        </button>
                    </div>
                @endforeach

                <form wire:submit="addLanguage" class="bg-white border border-dashed border-gray-300 rounded-xl p-5 space-y-4">
                    <p class="text-sm font-semibold text-gray-800">Adicionar idioma</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="newLanguageName" value="Idioma" />
                            <x-text-input id="newLanguageName" wire:model="newLanguageName" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('newLanguageName')" class="mt-1" />
                        </div>
                        <div>
                            <x-input-label for="newLanguageLevel" value="Nível" />
                            <select id="newLanguageLevel" wire:model="newLanguageLevel"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent text-sm">
                                @foreach ($languageLevels as $level)
                                    <option value="{{ $level->value }}">{{ $level->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <x-secondary-button type="submit">Adicionar idioma</x-secondary-button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Barra de navegação fixa --}}
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
            @if ($currentStepIndex > 0)
                <x-secondary-button type="button" wire:click="previousStep">← Voltar</x-secondary-button>
            @else
                <span></span>
            @endif

            @if ($activeTab === 'dados')
                <x-primary-button type="submit" form="dados-form">Continuar</x-primary-button>
            @elseif ($activeTab === 'resumo')
                <x-primary-button type="submit" form="resumo-form">Continuar</x-primary-button>
            @else
                <x-primary-button type="button" wire:click="nextStep">
                    {{ $isLastStep ? 'Escolher template →' : 'Continuar' }}
                </x-primary-button>
            @endif
        </div>
    </div>

</div>
