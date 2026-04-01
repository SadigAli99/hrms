<?php

namespace App\Enums\Candidate;

enum ParseStatus : string{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case PARSED = 'parsed';
    case FAILED = 'failed';
}