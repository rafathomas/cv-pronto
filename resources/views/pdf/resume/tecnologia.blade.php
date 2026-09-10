<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 28px 34px; }
        body { font-family: 'DejaVu Sans Mono', 'DejaVu Sans', monospace; color: #0f172a; font-size: 10.5px; line-height: 1.55; background: #f8fafc; }
        .cv-header { background: #0f172a; color: {{ $accentColor }}; padding: 14px 18px; margin-bottom: 14px; border-radius: 4px; }
        table.cv-header-table { background: #0f172a; margin-bottom: 14px; border-radius: 4px; width: 100%; }
        .cv-header-text { vertical-align: middle; padding: 14px 0 14px 18px; }
        .cv-header-photo { width: 56px; vertical-align: middle; text-align: right; padding: 14px 18px 14px 0; }
        .cv-photo-frame { width: 48px; height: 48px; border-radius: 50%; overflow: hidden; display: inline-block; border: 2px solid {{ $accentColor }}; }
        .cv-photo { width: 44px; height: 44px; }
        .cv-name { font-size: 19px; font-weight: bold; margin: 0 0 3px; color: {{ $accentColor }}; }
        .cv-contact { font-size: 9px; color: #94a3b8; margin: 0; }
        .cv-section { margin-bottom: 12px; }
        .cv-section-title { font-size: 10px; font-weight: bold; color: {{ $accentColor }}; margin: 0 0 6px; }
        .cv-section-title:before { content: "\25C6 "; color: {{ $accentColor }}; }
        .cv-entry { margin-bottom: 8px; }
        table.cv-entry-row { width: 100%; border-collapse: collapse; font-weight: bold; font-size: 10.5px; }
        table.cv-entry-row td { padding: 0; vertical-align: baseline; }
        .cv-entry-title { text-align: left; }
        .cv-entry-date { text-align: right; white-space: nowrap; font-weight: normal; color: #64748b; font-size: 9px; }
        .cv-text { margin: 3px 0 0; font-size: 10px; color: #334155; }
    </style>
</head>
<body>
    @include('pdf.resume._content', ['resume' => $resume, 'customizedSummary' => $customizedSummary ?? null, 'customizedExperiences' => $customizedExperiences ?? [], 'customizedSkills' => $customizedSkills ?? null])
</body>
</html>
