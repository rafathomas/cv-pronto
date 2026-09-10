<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 30px 36px; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #14201c; font-size: 11px; line-height: 1.55; }
        .cv-header { text-align: center; margin-bottom: 16px; }
        .cv-name { font-size: 24px; font-weight: bold; color: #16A34A; margin: 0 0 4px; letter-spacing: 0.3px; }
        .cv-contact { font-size: 9.5px; color: #555; margin: 0; }
        .cv-section { margin-bottom: 13px; }
        .cv-section-title { font-size: 10.5px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #16A34A; margin: 0 0 6px; }
        .cv-entry { margin-bottom: 9px; }
        .cv-entry-row { width: 100%; overflow: hidden; font-weight: bold; font-size: 11px; }
        .cv-entry-title { float: left; }
        .cv-entry-date { float: right; font-weight: normal; color: #16A34A; font-size: 9.5px; }
        .cv-text { margin: 3px 0 0; font-size: 10.5px; color: #333; }
    </style>
</head>
<body>
    @include('pdf.resume._content', ['resume' => $resume, 'customizedSummary' => $customizedSummary ?? null, 'customizedExperiences' => $customizedExperiences ?? [], 'customizedSkills' => $customizedSkills ?? null])
</body>
</html>
