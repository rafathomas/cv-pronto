<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 32px 40px; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #222; font-size: 10.5px; line-height: 1.6; }
        .cv-header { margin-bottom: 18px; }
        .cv-name { font-size: 19px; font-weight: normal; color: #111; margin: 0 0 3px; }
        .cv-contact { font-size: 9px; color: #888; margin: 0; }
        .cv-section { margin-bottom: 14px; }
        .cv-section-title { font-size: 9px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.5px; color: #999; margin: 0 0 7px; }
        .cv-entry { margin-bottom: 9px; }
        .cv-entry-row { width: 100%; overflow: hidden; font-weight: normal; font-size: 10.5px; }
        .cv-entry-title { float: left; font-weight: bold; }
        .cv-entry-date { float: right; color: #999; font-size: 9px; }
        .cv-text { margin: 2px 0 0; font-size: 10px; color: #444; }
    </style>
</head>
<body>
    @include('pdf.resume._content', ['resume' => $resume, 'customizedSummary' => $customizedSummary ?? null, 'customizedExperiences' => $customizedExperiences ?? [], 'customizedSkills' => $customizedSkills ?? null])
</body>
</html>
