<?php

namespace App\Enums\Application;

enum Source : string {
    case UPLOADED_CV = 'uploaded_cv';
    case TALENT_POOL = 'talent_pool';
    case MANUAL = 'manual';
    case LINKEDIN_IMPORT = 'linkedin_import';
}