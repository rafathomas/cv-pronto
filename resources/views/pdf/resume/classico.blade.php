<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 28px 34px; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #1a1a1a; font-size: 11px; line-height: 1.5; }
        .cv-header { border-bottom: 2px solid #1E3A5F; padding-bottom: 8px; margin-bottom: 14px; }
        table.cv-header-table { border-bottom: 2px solid #1E3A5F; margin-bottom: 14px; width: 100%; }
        .cv-header-text { vertical-align: middle; padding-bottom: 8px; }
        .cv-header-photo { width: 58px; vertical-align: middle; text-align: right; padding-bottom: 8px; }
        .cv-photo-frame { width: 52px; height: 52px; border-radius: 50%; overflow: hidden; display: inline-block; }
        .cv-photo { width: 52px; height: 52px; }
        .cv-name { font-size: 22px; font-weight: bold; color: #1E3A5F; margin: 0 0 4px; }
        .cv-contact { font-size: 9.5px; color: #555; margin: 0; }
        .cv-section { margin-bottom: 12px; }
        .cv-section-title { font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; color: #1E3A5F; border-bottom: 1px solid #ccc; padding-bottom: 3px; margin: 0 0 6px; }
        .cv-section-title:before { content: "\25A0 "; color: {{ $accentColor }}; font-size: 8px; }
        .cv-entry { margin-bottom: 8px; }
        table.cv-entry-row { width: 100%; border-collapse: collapse; font-weight: bold; font-size: 11px; }
        table.cv-entry-row td { padding: 0; vertical-align: baseline; }
        .cv-entry-title { text-align: left; }
        .cv-entry-date { text-align: right; white-space: nowrap; font-weight: normal; color: #666; font-size: 9.5px; }
        .cv-text { margin: 3px 0 0; font-size: 10.5px; color: #333; }
    </style>
</head>
<body>
    @include('pdf.resume._content', ['resume' => $resume, 'customizedSummary' => $customizedSummary ?? null, 'customizedExperiences' => $customizedExperiences ?? [], 'customizedSkills' => $customizedSkills ?? null])
</body>
</html>
