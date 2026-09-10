<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 0; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #1a1a1a; font-size: 11px; line-height: 1.5; margin: 0; }
        .cv-header { background: #1E3A5F; color: #fff; padding: 22px 36px; margin-bottom: 0; border-bottom: 4px solid {{ $accentColor }}; }
        .cv-name { font-size: 23px; font-weight: bold; margin: 0 0 4px; color: #fff; }
        .cv-contact { font-size: 9.5px; color: #cbd5e1; margin: 0; }
        .cv-section { margin: 0 36px 13px; padding-top: 13px; }
        .cv-section-title { font-size: 10.5px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.8px; color: #1E3A5F; border-bottom: 2px solid #1E3A5F; padding-bottom: 3px; margin: 0 0 6px; }
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
