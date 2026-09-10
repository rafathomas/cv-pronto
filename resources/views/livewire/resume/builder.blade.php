<div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Editar currículo</h1>
            <p class="text-sm text-gray-500 mt-1">As alterações são salvas por seção.</p>
        </div>
        <span x-data="{ show: false }"
              x-on:resume-saved.window="show = true; setTimeout(() => show = false, 2000)"
              x-show="show" x-cloak
              class="inline-flex items-center gap-1.5 text-xs font-semibold text-accent-dark bg-green-50 border border-green-200 rounded-full px-3 py-1.5">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
            Salvo com sucesso
        </span>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-6 overflow-x-auto border-b border-gray-200 mb-6" role="tablist">
        @foreach ([
            'dados' => 'Dados pessoais',
            'resumo' => 'Resumo',
            'experiencia' => 'Experiência',
            'formacao' => 'Formação',
            'cursos' => 'Cursos',
            'habilidades' => 'Habilidades',
            'idiomas' => 'Idiomas',
        ] as $tabKey => $label)
            <button type="button" wire:click="setTab('{{ $tabKey }}')"
                class="whitespace-nowrap pb-3 text-sm font-semibold border-b-2 -mb-px transition
                    {{ $activeTab === $tabKey ? 'border-accent text-brand-dark' : 'border-transparent text-gray-500 hover:text-gray-800' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Dados pessoais --}}
    @if ($activeTab === 'dados')
        <form wire:submit="savePersonalData" class="bg-white border border-gray-200 rounded-xl p-6 space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <x-input-label for="fullName" value="Nome completo" />
                    <x-text-input id="fullName" wire:model="fullName" class="mt-1 block w-full" />
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
                <div>
                    <x-input-label for="portfolioUrl" value="Site/portfólio (opcional)" />
                    <x-text-input id="portfolioUrl" wire:model="portfolioUrl" placeholder="seusite.com" class="mt-1 block w-full" />
                </div>
            </div>
            <div class="flex justify-end">
                <x-primary-button type="submit">Salvar dados pessoais</x-primary-button>
            </div>
        </form>
    @endif

    {{-- Resumo --}}
    @if ($activeTab === 'resumo')
        <form wire:submit="saveSummary" class="bg-white border border-gray-200 rounded-xl p-6 space-y-4">
            <div class="flex items-center justify-between">
                <x-input-label for="professionalSummary" value="Resumo profissional" />
                <span class="text-xs text-gray-400">A IA de melhoria será integrada na próxima etapa</span>
            </div>
            <textarea id="professionalSummary" wire:model="professionalSummary" rows="6"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent text-sm"
                placeholder="Fale sobre sua área, principais ferramentas e o tipo de impacto que você gera."></textarea>
            <x-input-error :messages="$errors->get('professionalSummary')" class="mt-1" />
            <div class="flex justify-end">
                <x-primary-button type="submit">Salvar resumo</x-primary-button>
            </div>
        </form>
    @endif

    {{-- Experiência --}}
    @if ($activeTab === 'experiencia')
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
                        <button wire:click="deleteExperience({{ $experience->id }})" wire:confirm="Remover esta experiência?"
                            class="text-gray-400 hover:text-red-600" aria-label="Remover experiência">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
                        </button>
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
    @endif

    {{-- Formação --}}
    @if ($activeTab === 'formacao')
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
    @endif

    {{-- Cursos --}}
    @if ($activeTab === 'cursos')
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
    @endif

    {{-- Habilidades --}}
    @if ($activeTab === 'habilidades')
        <div class="bg-white border border-gray-200 rounded-xl p-6 space-y-4">
            <x-input-label value="Habilidades" />
            <div class="flex flex-wrap gap-2">
                @foreach ($resume->skills as $skill)
                    <span wire:key="skill-{{ $skill->id }}" class="inline-flex items-center gap-2 bg-gray-100 border border-gray-200 rounded-full px-3 py-1.5 text-sm font-medium text-gray-800">
                        {{ $skill->name }}
                        <button wire:click="removeSkill({{ $skill->id }})" class="text-gray-400 hover:text-red-600" aria-label="Remover {{ $skill->name }}">×</button>
                    </span>
                @endforeach
            </div>
            <form wire:submit="addSkill" class="flex gap-2">
                <x-text-input wire:model="newSkillName" placeholder="Adicionar habilidade..." class="flex-1" />
                <x-secondary-button type="submit">Adicionar</x-secondary-button>
            </form>
            <x-input-error :messages="$errors->get('newSkillName')" class="mt-1" />
        </div>
    @endif

    {{-- Idiomas --}}
    @if ($activeTab === 'idiomas')
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
    @endif

</div>
