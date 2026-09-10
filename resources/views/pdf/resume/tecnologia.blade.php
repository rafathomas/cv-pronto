<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 30px 36px; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #1e293b; font-size: 10.5px; line-height: 1.55; }
        .cv-header, table.cv-header-table { margin-bottom: 16px; padding-bottom: 8px; border-bottom: 3px solid {{ $accentColor }}; width: 100%; }
        .cv-header-text { vertical-align: middle; }
        .cv-header-photo { width: 56px; vertical-align: middle; text-align: right; }
        .cv-photo-frame { width: 50px; height: 50px; border-radius: 50%; overflow: hidden; display: inline-block; }
        .cv-photo { width: 50px; height: 50px; }
        .cv-name { font-size: 21px; font-weight: bold; margin: 0 0 3px; color: #0f172a; }
        .cv-contact { font-size: 9.5px; color: #64748b; margin: 0; }
        .cv-section { margin-bottom: 13px; }
        .cv-section-title { font-size: 10.5px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.8px; color: #0f172a; border-left: 3px solid {{ $accentColor }}; padding-left: 7px; margin: 0 0 7px; }
        .cv-entry { margin-bottom: 8px; }
        table.cv-entry-row { width: 100%; border-collapse: collapse; font-weight: bold; font-size: 10.5px; }
        table.cv-entry-row td { padding: 0; vertical-align: baseline; }
        .cv-entry-title { text-align: left; color: #0f172a; }
        .cv-entry-date { text-align: right; white-space: nowrap; font-weight: normal; color: {{ $accentColor }}; font-size: 9px; }
        .cv-text { margin: 3px 0 0; font-size: 10px; color: #334155; }
    </style>
</head>
<body>
    @include('pdf.resume._content', ['resume' => $resume, 'customizedSummary' => $customizedSummary ?? null, 'customizedExperiences' => $customizedExperiences ?? [], 'customizedSkills' => $customizedSkills ?? null])
</body>
</html>
