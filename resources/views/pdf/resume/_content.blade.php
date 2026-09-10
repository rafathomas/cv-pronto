{{-- Conteúdo do currículo, compartilhado por todos os templates. --}}
<div class="cv-header">
    <h1 class="cv-name">{{ $resume->full_name }}</h1>
    <p class="cv-contact">
        {{ collect([
            $resume->email,
            $resume->phone,
            trim(($resume->city ?? '').($resume->city && $resume->state ? ', ' : '').($resume->state ?? '')),
            $resume->linkedin_url,
            $resume->github_url,
            $resume->portfolio_url,
        ])->filter()->implode('   ·   ') }}
    </p>
</div>

@if ($summary = $customizedSummary ?? $resume->professional_summary)
    <div class="cv-section">
        <h2 class="cv-section-title">Resumo</h2>
        <p class="cv-text">{{ $summary }}</p>
    </div>
@endif

@if ($resume->experiences->isNotEmpty())
    <div class="cv-section">
        <h2 class="cv-section-title">Experiência</h2>
        @foreach ($resume->experiences as $experience)
            <div class="cv-entry">
                <table class="cv-entry-row"><tr>
                    <td class="cv-entry-title">{{ $experience->position }} · {{ $experience->company }}</td>
                    <td class="cv-entry-date">
                        {{ $experience->start_date->format('m/Y') }} –
                        {{ $experience->is_current ? 'Atual' : ($experience->end_date?->format('m/Y') ?? '') }}
                    </td>
                </tr></table>
                @php $description = $customizedExperiences[$experience->id] ?? $experience->description; @endphp
                @if ($description)
                    <p class="cv-text">{{ $description }}</p>
                @endif
            </div>
        @endforeach
    </div>
@endif

@if ($resume->education->isNotEmpty())
    <div class="cv-section">
        <h2 class="cv-section-title">Formação</h2>
        @foreach ($resume->education as $education)
            <div class="cv-entry">
                <table class="cv-entry-row"><tr>
                    <td class="cv-entry-title">{{ $education->course }} · {{ $education->institution }}</td>
                    <td class="cv-entry-date">
                        @if ($education->start_date || $education->end_date)
                            {{ $education->start_date?->format('Y') }} – {{ $education->end_date?->format('Y') ?? 'Atual' }}
                        @endif
                    </td>
                </tr></table>
                @if ($education->degree)
                    <p class="cv-text">{{ $education->degree }}</p>
                @endif
            </div>
        @endforeach
    </div>
@endif

@if ($resume->courses->isNotEmpty())
    <div class="cv-section">
        <h2 class="cv-section-title">Cursos</h2>
        <p class="cv-text">{{ $resume->courses->pluck('name')->implode(' · ') }}</p>
    </div>
@endif

@php $skillList = $customizedSkills ?? $resume->skills->pluck('name')->all(); @endphp
@if (count($skillList))
    <div class="cv-section">
        <h2 class="cv-section-title">Habilidades</h2>
        <p class="cv-text">{{ implode(' · ', $skillList) }}</p>
    </div>
@endif

@if ($resume->languages->isNotEmpty())
    <div class="cv-section">
        <h2 class="cv-section-title">Idiomas</h2>
        <p class="cv-text">
            {{ $resume->languages->map(fn ($l) => $l->name.' ('.$l->level->label().')')->implode(' · ') }}
        </p>
    </div>
@endif
