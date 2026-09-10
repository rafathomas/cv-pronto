<?php

namespace App\Domain\Resume\Enums;

enum ResumeSource: string
{
    case Manual = 'manual';
    case Import = 'import';
}
