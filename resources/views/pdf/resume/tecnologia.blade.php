<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 28px 34px; }
        body { font-family: 'DejaVu Sans Mono', 'DejaVu Sans', monospace; color: #0f172a; font-size: 10.5px; line-height: 1.55; background: #f8fafc; }
        .cv-header { background: #0f172a; color: #4ade80; padding: 14px 18px; margin-bottom: 14px; border-radius: 4px; }
        .cv-name { font-size: 19px; font-weight: bold; margin: 0 0 3px; color: #4ade80; }
        .cv-contact { font-size: 9px; color: #94a3b8; margin: 0; }
        .cv-section { margin-bottom: 12px; }
        .cv-section-title { font-size: 10px; font-weight: bold; color: #16A34A; margin: 0 0 6px; }
        .cv-section-title:before { content: "> "; }
        .cv-entry { margin-bottom: 8px; }
        .cv-entry-row { width: 100%; overflow: hidden; font-weight: bold; font-size: 10.5px; }
        .cv-entry-title { float: left; }
        .cv-entry-date { float: right; font-weight: normal; color: #64748b; font-size: 9px; }
        .cv-text { margin: 3px 0 0; font-size: 10px; color: #334155; }
    </style>
</head>
<body>
    @include('pdf.resume._content', ['resume' => $resume, 'customizedSummary' => $customizedSummary ?? null, 'customizedExperiences' => $customizedExperiences ?? [], 'customizedSkills' => $customizedSkills ?? null])
</body>
</html>
