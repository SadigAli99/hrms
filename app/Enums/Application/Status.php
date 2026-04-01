<?php 

namespace App\Enums\Application;

enum Status : string {
    case NEW = 'new';
    case AI_ANALYZED = 'ai_analyzed';
    case SHORTLISTED = 'shortlisted';
    case INTERVIEW_SCHEDULED = 'interview_scheduled';
    case INTERVIEWED = 'interviewed';
    case FINAL_REVIEW = 'final_review';
    case OFFER_PENDING = 'offer_pending';
    case HIRED = 'hired';
    case REJECTED = 'rejected';
    case ON_HOLD = 'on_hold';
}