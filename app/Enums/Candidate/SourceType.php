<?php

namespace App\Enums\Candidate;

enum SourceType : string
{
    case UPLOAD_CV = 'uploaded_cv';
    case TALENT_POOL = 'talent_pool';
    case MANUAL = 'manual';
    case LINKEDIN_IMPORT = 'linkedin_import';
}