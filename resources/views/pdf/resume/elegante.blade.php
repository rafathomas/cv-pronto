<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 34px 0 34px 0; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #26262b; font-size: 10.5px; line-height: 1.55; margin: 0; }

        table.cv-page { width: 100%; border-collapse: collapse; }
        td.cv-rule { width: 30px; background: {{ $accentColor }}; }
        td.cv-body { padding: 0 34px 0 26px; }

        .cv-header, table.cv-header-table { margin-bottom: 18px; padding-bottom: 14px; border-bottom: 1px solid #e8e8ec; width: 100%; }
        .cv-header-text { vertical-align: middle; }
        .cv-header-photo { width: 60px; vertical-align: middle; text-align: right; }
        .cv-photo-frame { width: 54px; height: 54px; border-radius: 50%; overflow: hidden; display: inline-block; }
        .cv-photo { width: 54px; height: 54px; }
        .cv-name { font-size: 22px; font-weight: bold; color: #1a1a1f; margin: 0 0 4px; letter-spacing: 0.2px; }
        .cv-contact { font-size: 9.5px; color: #8a8a92; margin: 0; }
        .cv-section { margin-bottom: 14px; }
        .cv-section-title { font-size: 10.5px; font-weight: bold; text-transform: uppercase; letter-spacing: 1.2px; color: {{ $accentColor }}; margin: 0 0 8px; }
        .cv-entry { margin-bottom: 9px; }
        table.cv-entry-row { width: 100%; border-collapse: collapse; font-weight: bold; font-size: 10.5px; }
        table.cv-entry-row td { padding: 0; vertical-align: baseline; }
        .cv-entry-title { text-align: left; color: #1a1a1f; }
        .cv-entry-date { text-align: right; white-space: nowrap; font-weight: normal; color: #9a9aa2; font-size: 9px; }
        .cv-text { margin: 3px 0 0; font-size: 10px; color: #4a4a52; }
    </style>
</head>
<body>
    <table class="cv-page"><tr>
        <td class="cv-rule">&nbsp;</td>
        <td class="cv-body">
            @include('pdf.resume._content', ['resume' => $resume, 'customizedSummary' => $customizedSummary ?? null, 'customizedExperiences' => $customizedExperiences ?? [], 'customizedSkills' => $customizedSkills ?? null])
        </td>
    </tr></table>
</body>
</html>
