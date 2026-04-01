<?php

namespace App\Enums\Candidate;

enum ParsedSource : string {
    case CV_PARSER = 'cv_parser';
    case LINKEDIN_IMPORT = 'linkedin_import';
    case MANUAL_EDIT = 'manual_edit';
    case MERGED = 'merged';
}