<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0; }
        * { box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #1a1a1a; font-size: 10.5px; line-height: 1.5; margin: 0; }

        table.cv-shell { width: 100%; border-collapse: collapse; }
        td.cv-sidebar { width: 34%; background: #1E3A5F; color: #fff; padding: 28px 20px; vertical-align: top; }
        td.cv-main { width: 66%; background: #fff; padding: 30px 28px; vertical-align: top; }

        .cv-avatar { width: 58px; height: 58px; border-radius: 50%; background: {{ $accentColor }}; color: #fff; font-size: 22px; font-weight: bold; text-align: center; line-height: 58px; margin-bottom: 14px; }
        .cv-sidebar h1 { font-size: 17px; font-weight: bold; color: #fff; margin: 0 0 3px; line-height: 1.25; }
        .cv-sidebar .cv-role { font-size: 9.5px; color: #9fc3e0; margin: 0 0 18px; text-transform: uppercase; letter-spacing: 0.5px; }

        .side-title { font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #7fb3da; border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 4px; margin: 18px 0 8px; }
        .side-title.first { margin-top: 0; }
        .side-line { font-size: 9px; color: #dfe9f2; margin-bottom: 5px; word-wrap: break-word; }

        .skill-row { margin-bottom: 8px; }
        .skill-name { font-size: 9px; color: #eef4fa; margin-bottom: 2px; }
        .skill-bar-track { background: rgba(255,255,255,0.18); border-radius: 3px; height: 5px; width: 100%; }
        .skill-bar-fill { background: {{ $accentColor }}; border-radius: 3px; height: 5px; }

        .lang-row { font-size: 9px; color: #dfe9f2; margin-bottom: 4px; }
        .lang-row b { color: #fff; }

        .cv-main h2.section { font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.6px; color: #1E3A5F; border-bottom: 2px solid {{ $accentColor }}; padding-bottom: 4px; margin: 0 0 10px; }
        .cv-main .section-block { margin-bottom: 18px; }
        .cv-text { font-size: 10px; color: #333; margin: 0; }

        table.timeline { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        table.timeline td { vertical-align: top; padding: 0; }
        td.timeline-rail { width: 14px; padding-top: 3px; }
        .timeline-dot { display: block; width: 7px; height: 7px; border-radius: 50%; background: {{ $accentColor }}; }
        td.timeline-body { padding-bottom: 13px; border-left: 2px solid #E2E8F0; padding-left: 10px; }
        table.timeline tr:last-child td.timeline-body { border-left: 2px solid transparent; }
        table.entry-row { width: 100%; border-collapse: collapse; }
        table.entry-row td { padding: 0; vertical-align: baseline; }
        .entry-title { font-weight: bold; font-size: 10.5px; color: #14213D; text-align: left; }
        .entry-date { font-size: 9px; color: {{ $accentColor }}; font-weight: bold; text-align: right; white-space: nowrap; }
        .entry-sub { font-size: 9.5px; color: #64748B; margin: 1px 0 4px; }
    </style>
</head>
<body>
    <table class="cv-shell">
        <tr>
            <td class="cv-sidebar">
                <div class="cv-avatar">{{ mb_substr($resume->full_name, 0, 1) }}</div>
                <h1>{{ $resume->full_name }}</h1>
                @if ($resume->experiences->isNotEmpty())
                    <p class="cv-role">{{ $resume->experiences->first()->position }}</p>
                @endif

                <div class="side-title first">Contato</div>
                @if ($resume->email)<div class="side-line">{{ $resume->email }}</div>@endif
                @if ($resume->phone)<div class="side-line">{{ $resume->phone }}</div>@endif
                @if ($resume->city)<div class="side-line">{{ $resume->city }}@if($resume->state), {{ $resume->state }}@endif</div>@endif
                @if ($resume->linkedin_url)<div class="side-line">{{ $resume->linkedin_url }}</div>@endif
                @if ($resume->github_url)<div class="side-line">{{ $resume->github_url }}</div>@endif
                @if ($resume->portfolio_url)<div class="side-line">{{ $resume->portfolio_url }}</div>@endif

                @php $skillList = $customizedSkills ?? $resume->skills->pluck('name')->all(); @endphp
                @if (count($skillList))
                    <div class="side-title">Habilidades</div>
                    @foreach ($skillList as $i => $skill)
                        <div class="skill-row">
                            <div class="skill-name">{{ $skill }}</div>
                            <div class="skill-bar-track"><div class="skill-bar-fill" style="width: {{ max(55, 92 - $i * 6) }}%;"></div></div>
                        </div>
                    @endforeach
                @endif

                @if ($resume->languages->isNotEmpty())
                    <div class="side-title">Idiomas</div>
                    @foreach ($resume->languages as $language)
                        <div class="lang-row"><b>{{ $language->name }}</b> — {{ $language->level->label() }}</div>
                    @endforeach
                @endif

                @if ($resume->courses->isNotEmpty())
                    <div class="side-title">Cursos</div>
                    @foreach ($resume->courses as $course)
                        <div class="side-line">{{ $course->name }}</div>
                    @endforeach
                @endif
            </td>

            <td class="cv-main">
                @if ($summary = $customizedSummary ?? $resume->professional_summary)
                    <div class="section-block">
                        <h2 class="section">Resumo</h2>
                        <p class="cv-text">{{ $summary }}</p>
                    </div>
                @endif

                @if ($resume->experiences->isNotEmpty())
                    <div class="section-block">
                        <h2 class="section">Experiência</h2>
                        <table class="timeline">
                            @foreach ($resume->experiences as $experience)
                                <tr>
                                    <td class="timeline-rail"><span class="timeline-dot"></span></td>
                                    <td class="timeline-body">
                                        <table class="entry-row"><tr>
                                            <td class="entry-title">{{ $experience->position }}</td>
                                            <td class="entry-date">
                                                {{ $experience->start_date->format('m/Y') }} –
                                                {{ $experience->is_current ? 'Atual' : ($experience->end_date?->format('m/Y') ?? '') }}
                                            </td>
                                        </tr></table>
                                        <p class="entry-sub">{{ $experience->company }}</p>
                                        @php $description = $customizedExperiences[$experience->id] ?? $experience->description; @endphp
                                        @if ($description)
                                            <p class="cv-text">{{ $description }}</p>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @endif

                @if ($resume->education->isNotEmpty())
                    <div class="section-block">
                        <h2 class="section">Formação</h2>
                        <table class="timeline">
                            @foreach ($resume->education as $education)
                                <tr>
                                    <td class="timeline-rail"><span class="timeline-dot"></span></td>
                                    <td class="timeline-body">
                                        <table class="entry-row"><tr>
                                            <td class="entry-title">{{ $education->course }}</td>
                                            <td class="entry-date">
                                                @if ($education->start_date || $education->end_date)
                                                    {{ $education->start_date?->format('Y') }} – {{ $education->end_date?->format('Y') ?? 'Atual' }}
                                                @endif
                                            </td>
                                        </tr></table>
                                        <p class="entry-sub">{{ $education->institution }}@if($education->degree) · {{ $education->degree }}@endif</p>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @endif
            </td>
        </tr>
    </table>
</body>
</html>
