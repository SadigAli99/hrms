<?php

namespace App\Enums\Candidate;

enum ProfileStatus : string
{
    case NEW = 'new';
    case ACTIVE = 'active';
    case ARCHIVED = 'archived';
    case BLOCKED = 'blocked';
    case TALENT_POOL = 'talent_pool';
    case HIRED = 'hired';
}
