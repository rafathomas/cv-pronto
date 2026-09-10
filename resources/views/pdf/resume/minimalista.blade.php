<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 32px 40px; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #222; font-size: 10.5px; line-height: 1.6; }
        .cv-header, table.cv-header-table { margin-bottom: 18px; width: 100%; }
        .cv-header-text { vertical-align: middle; }
        .cv-header-photo { width: 56px; vertical-align: middle; text-align: right; }
        .cv-photo-frame { width: 50px; height: 50px; border-radius: 50%; overflow: hidden; display: inline-block; }
        .cv-photo { width: 50px; height: 50px; }
        .cv-name { font-size: 19px; font-weight: normal; color: #111; margin: 0 0 5px; padding-bottom: 8px; border-bottom: 2px solid {{ $accentColor }}; display: inline-block; }
        .cv-contact { font-size: 9px; color: #888; margin: 0; }
        .cv-section { margin-bottom: 14px; }
        .cv-section-title { font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: #999; margin: 0 0 7px; }
        .cv-entry { margin-bottom: 9px; }
        table.cv-entry-row { width: 100%; border-collapse: collapse; font-weight: normal; font-size: 10.5px; }
        table.cv-entry-row td { padding: 0; vertical-align: baseline; }
        .cv-entry-title { text-align: left; font-weight: bold; }
        .cv-entry-date { text-align: right; white-space: nowrap; color: #999; font-size: 9px; }
        .cv-text { margin: 2px 0 0; font-size: 10px; color: #444; }
    </style>
</head>
<body>
    @include('pdf.resume._content', ['resume' => $resume, 'customizedSummary' => $customizedSummary ?? null, 'customizedExperiences' => $customizedExperiences ?? [], 'customizedSkills' => $customizedSkills ?? null])
</body>
</html>
