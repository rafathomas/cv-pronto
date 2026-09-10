<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #1a1a1a; font-size: 10.5px; line-height: 1.55; margin: 0; }

        .cv-band { background: {{ $accentColor }}; padding: 30px 36px 44px; text-align: center; }
        .cv-avatar-frame { width: 74px; height: 74px; border-radius: 50%; overflow: hidden; margin: 0 auto 12px; border: 3px solid rgba(255,255,255,0.6); }
        .cv-avatar-photo { width: 74px; height: 74px; }
        .cv-avatar-fallback { width: 74px; height: 74px; border-radius: 50%; margin: 0 auto 12px; background: rgba(255,255,255,0.22); color: #fff; font-size: 26px; font-weight: bold; text-align: center; line-height: 74px; }
        .cv-name { font-size: 22px; font-weight: bold; color: #fff; margin: 0 0 4px; }
        .cv-contact { font-size: 9.5px; color: rgba(255,255,255,0.9); margin: 0; }

        .cv-body { padding: 26px 40px 30px; margin-top: -22px; background: #fff; border-radius: 14px 14px 0 0; }
        .cv-section { margin-bottom: 14px; }
        .cv-section-title { font-size: 10.5px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.8px; color: #1a1a1a; border-left: 3px solid {{ $accentColor }}; padding-left: 8px; margin: 0 0 8px; }
        .cv-entry { margin-bottom: 9px; }
        table.cv-entry-row { width: 100%; border-collapse: collapse; font-weight: bold; font-size: 10.5px; }
        table.cv-entry-row td { padding: 0; vertical-align: baseline; }
        .cv-entry-title { text-align: left; color: #1a1a1a; }
        .cv-entry-date { text-align: right; white-space: nowrap; font-weight: normal; color: {{ $accentColor }}; font-size: 9px; }
        .cv-text { margin: 3px 0 0; font-size: 10px; color: #45454d; }
    </style>
</head>
<body>
    <div class="cv-band">
        @if ($resume->hasPhoto())
            <div class="cv-avatar-frame"><img src="{{ $resume->photoDataUri() }}" class="cv-avatar-photo" alt=""></div>
        @else
            <div class="cv-avatar-fallback">{{ mb_substr($resume->full_name, 0, 1) }}</div>
        @endif
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

    <div class="cv-body">
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
    </div>
</body>
</html>
