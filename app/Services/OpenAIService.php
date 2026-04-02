<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenAIService
{
    public function analyze(
        string $cvText,
        ?string $fileName = null,
        array $vacancyRequirements = [],
        array $vacancyContext = []
    ): array {
        try {

            $prompt = $this->buildPrompt($cvText, $fileName, $vacancyRequirements, $vacancyContext);
            $rawResponse = $this->sendRequest($prompt);
            $parsed = $this->parseResponse($rawResponse);
            $validated = $this->validateResponse($parsed);

            if (!$validated['is_cv']) {
                return $this->buildFailureResponse(
                    $validated['rejection_reason'] ?? 'Document is not a CV'
                );
            }

            return [
                'success' => true,
                'is_cv' => true,
                'document_type' => $validated['document_type'],
                'document_confidence' => $validated['document_confidence'],
                'rejection_reason' => null,
                'data' =>  $validated
            ];
        } catch (\Exception $ex) {
            return $this->buildFailureResponse($ex->getMessage());
        }
    }

    public function buildPrompt(
        string $cvText,
        ?string $fileName = null,
        array $vacancyRequirements = [],
        array $vacancyContext = []
    ): string {
        $schema = $this->getResponseSchema();

        $schemaText = json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $normalizedRequirements = $this->normalizeRequirementsForPrompt($vacancyRequirements);

        $vacancyContextText = !empty($vacancyContext)
            ? json_encode($vacancyContext, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            : 'No vacancy context provided.';

        $requirementsText = !empty($normalizedRequirements)
            ? json_encode($normalizedRequirements, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            : 'No vacancy requirements provided.';

        return "You are an HR CV parsing and candidate evaluation assistant.

            High-priority requirements are more important than medium or low-priority ones.
            Critical requirements must influence the overall score strongly.
            If critical required skills are missing, the overall score must drop significantly.
            Do not overvalue languages, certifications, or secondary criteria when core job skills are weak.
            Use secondary requirements only as supporting signals, not as dominant scoring factors.

            Your task:
            1. Determine whether the provided document is a CV/resume or not.
            2. If it is a CV, extract structured candidate information.
            3. If vacancy requirements are provided, evaluate the candidate against them.
            4. Return ONLY valid JSON.
            5. Do not return markdown.
            6. Do not return explanations.
            7. Do not wrap the response in code fences.
            8. Evaluate the candidate against vacancy requirements using requirement priority.
            9. Make core high-priority requirements dominate the scoring logic.
            10. identify critical missing requirements
            11. identify candidate risk flags
            12. evaluate seniority fit
            13. evaluate salary fit if enough information exists
            14. return per-requirement matching analysis
            15. Calculate overall_score using weighted priority logic instead of simple averaging
            16. Apply score caps when critical requirements are missing
            17. Add explicit risk flags for overqualification and salary mismatch when supported by the evidence
            18. Enforce hard score caps when critical core skill requirements are missing

            Rules:
            - All human-readable text fields must be written in Azerbaijani
            - Write rejection_reason, professional_summary, pros_text, cons_text, notes, overall_score_reasoning, and score_adjustment_notes_json.reason in Azerbaijani
            - Keep JSON keys in English exactly as defined in the schema
            - Set is_cv to true only if the document is clearly a CV/resume.
            - If the document is not a CV, set is_cv to false.
            - If is_cv is false:
                - set document_type appropriately
                - fill rejection_reason
                - keep candidate fields null or empty arrays where appropriate
            - document_confidence must be an integer between 0 and 100
            - scores must be numbers between 0 and 100
            - skills_json, experience_json, education_json, languages_json, certifications_json, matched_skills_json, missing_skills_json must be arrays
            - Treat priority_label='critical' as the strongest factor in scoring
            - Treat priority_label='supportive' as a medium-impact factor
            - Treat priority_label='secondary' as a low-impact factor
            - If required=true and priority_label='critical', missing that requirement must heavily reduce the score
            - requirement_matches_json must include all vacancy requirements if provided
            - critical_missing_json should only include unmet critical requirements
            - risk_flags_json should contain concise HR-relevant concerns
            - if salary info is unavailable, set salary_fit to unknown
            - requirement_matches_json must include one item for each vacancy requirement
            - each item must contain:
                - requirement
                - match_status
                - confidence
                - notes
                - weight_applied
            - match_status must be one of: matched, partial, missing, unclear
            - confidence must be an integer between 0 and 100
            - weight_applied should reflect the requirement priority in scoring
            - overall_score must be priority-weighted, not a simple average
            - high-priority requirements must affect the score more than medium or low-priority ones
            - if one or more critical required requirements are missing, overall_score must be capped
            - if critical core skill requirements are missing, overall_score must not exceed 45
            - languages alone must not significantly raise the overall score when core role skills are weak
            - certifications or secondary criteria must not outweigh missing critical skills
            - use language score as a supporting factor, not a dominant factor
            - if vacancy salary range is provided, use it in salary_fit evaluation
            - if candidate salary expectation is available, compare it with the vacancy salary range
            - salary_fit must be one of: below_range, within_range, above_range, unknown
            - if candidate salary expectation is not available, set salary_fit to unknown
            - If the CV mentions salary expectations, extract expected_salary as a number.
            - If no salary expectation is found, set expected_salary to null.
            - use vacancy description and requirements_text only as secondary context
            - structured vacancy requirements remain the primary source for scoring and matching
            - do not let description keywords override missing critical structured requirements
            - use description keywords to refine interpretation, not to dominate scoring
            - score_adjustment_notes_json must explain why the overall score was reduced or adjusted
            - each score_adjustment_notes_json item must contain:
                - factor
                - impact
                - reason
            - use negative impact values when a mismatch lowers the score
            - overall_score_reasoning must summarize the final score logic in a short human-readable sentence

            Scoring constraints:
            - Missing critical required skill => overall_score max 45
            - Weak core skill alignment + strong language match => do not inflate overall_score
            - Supportive or secondary matches can improve the score, but cannot override critical gaps
            - if a critical core skill is missing, overall_score must not exceed 45
            - if the candidate appears significantly overqualified for the vacancy seniority level, add an overqualified risk flag
            - if expected salary is above the vacancy salary range, add a salary mismatch risk flag
            - risk_flags_json must explicitly include overqualified and salary mismatch when applicable
            - critical core skill gaps must be reflected both in critical_missing_json and requirement_matches_json
            - Overqualified candidate => add 'overqualified' to risk_flags_json when evidence is strong
            - Salary expectation above vacancy range => add 'salary_mismatch' to risk_flags_json

            Expected JSON schema:

            {$schemaText}

            File name: " . ($fileName ?: 'Unknown file') . "

            Vacancy context :
            {$vacancyContextText}

            Vacancy requirements with priority:

            {$requirementsText}

            Document content:

            {$cvText}

            Final output language rule:
            - Return valid JSON only
            - Human-readable explanatory text must be in Azerbaijani
            - Do not answer in English unless the source text itself must be quoted briefly

            If the response is not valid JSON, your answer is invalid.";
    }

    public function parseResponse(string $response): array
    {
        $response = trim($response);

        if ($response === '') {
            throw new \RuntimeException('AI response is empty');
        }

        $data = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            throw new \RuntimeException('AI response is not valid JSON');
        }

        return $data;
    }

    public function validateResponse(array $data): array
    {
        foreach (['is_cv', 'document_type', 'document_confidence'] as $field) {
            if (!array_key_exists($field, $data)) {
                throw new \RuntimeException("Missing required field : {$field}");
            }
        }

        return [
            'is_cv' => (bool) $data['is_cv'],
            'document_type' => (string) ($data['document_type'] ?? 'other'),
            'document_confidence' => max(0, min(100, (int) ($data['document_confidence'] ?? 0))),
            'rejection_reason' => $data['rejection_reason'] ?? null,
            'full_name' => $data['full_name'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'location' => $data['location'] ?? null,
            'current_title' => $data['current_title'] ?? null,
            'total_experience_years' => $data['total_experience_years'] ?? null,
            'highest_education' => $data['highest_education'] ?? null,
            'professional_summary' => $data['professional_summary'] ?? null,
            'skills_json' => is_array($data['skills_json'] ?? null) ? $data['skills_json'] : [],
            'experience_json' => is_array($data['experience_json'] ?? null) ? $data['experience_json'] : [],
            'education_json' => is_array($data['education_json'] ?? null) ? $data['education_json'] : [],
            'languages_json' => is_array($data['languages_json'] ?? null) ? $data['languages_json'] : [],
            'certifications_json' => is_array($data['certifications_json'] ?? null) ? $data['certifications_json'] : [],
            'overall_score' => max(0, min(100, (int) ($data['overall_score'] ?? 0))),
            'skills_score' => max(0, min(100, (int) ($data['skills_score'] ?? 0))),
            'languages_score' => max(0, min(100, (int) ($data['languages_score'] ?? 0))),
            'experience_score' => max(0, min(100, (int) ($data['experience_score'] ?? 0))),
            'education_score' => max(0, min(100, (int) ($data['education_score'] ?? 0))),
            'pros_text' => $data['pros_text'] ?? null,
            'cons_text' => $data['cons_text'] ?? null,
            'matched_skills_json' => is_array($data['matched_skills_json'] ?? null) ? $data['matched_skills_json'] : [],
            'missing_skills_json' => is_array($data['missing_skills_json'] ?? null) ? $data['missing_skills_json'] : [],
            'critical_missing_json' => is_array($data['critical_missing_json'] ?? null) ? $data['critical_missing_json'] : [],
            'risk_flags_json' => is_array($data['risk_flags_json'] ?? null) ? $data['risk_flags_json'] : [],
            'seniority_fit' => $data['seniority_fit'] ?? null,
            'salary_fit' => $data['salary_fit'] ?? null,
            'requirement_matches_json' => array_map(
                fn($item) => [
                    'requirement' => $item['requirement'] ?? null,
                    'match_status' => $item['match_status'] ?? 'unclear',
                    'confidence' => max(0, min(100, (int) ($item['confidence'] ?? 0))),
                    'notes' => $item['notes'] ?? null,
                    'weight_applied' => $item['weight_applied'] ?? null,
                ],
                is_array($data['requirement_matches_json'] ?? null) ? $data['requirement_matches_json'] : []
            ),
            'expected_salary' => $data['expected_salary'] ?? null,
            'score_adjustment_notes_json' => array_map(
                fn($item) => [
                    'factor' => $item['factor'] ?? null,
                    'impact' => (int) ($item['impact'] ?? 0),
                    'reason' => $item['reason'] ?? null,
                ],
                is_array($data['score_adjustment_notes_json'] ?? null) ? $data['score_adjustment_notes_json'] : []
            ),
            'overall_score_reasoning' => $data['overall_score_reasoning'] ?? null,
        ];
    }

    protected function getResponseSchema(): array
    {
        return [
            'is_cv' => 'boolean',
            'document_type' => 'string',
            'document_confidence' => 'integer',
            'rejection_reason' => 'string|null',
            'full_name' => 'string|null',
            'email' => 'string|null',
            'phone' => 'string|null',
            'location' => 'string|null',
            'current_title' => 'string|null',
            'total_experience_years' => 'number|null',
            'highest_education' => 'string|null',
            'professional_summary' => 'string|null',
            'skills_json' => 'array',
            'experience_json' => 'array',
            'education_json' => 'array',
            'languages_json' => 'array',
            'certifications_json' => 'array',
            'overall_score' => 'number|null',
            'skills_score' => 'number|null',
            'experience_score' => 'number|null',
            'education_score' => 'number|null',
            'languages_score' => 'number|null',
            'pros_text' => 'string|null',
            'cons_text' => 'string|null',
            'matched_skills_json' => 'array',
            'missing_skills_json' => 'array',
            'critical_missing_json' => 'array',
            'risk_flags_json' => 'array',
            'seniority_fit' => 'string|null',
            'salary_fit' => 'string|null',
            'requirement_matches_json' => 'array of objects: requirement, match_status, confidence, notes, weight_applied',
            'expected_salary' => 'number|null',
            'score_adjustment_notes_json' => 'array',
            'overall_score_reasoning' => 'string|null',
        ];
    }

    protected function sendRequest(string $prompt): string
    {
        $response = Http::withToken(config('services.openai.api_key'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => config('services.openai.model'),
                'messages' => [
                    ['role' => 'system', 'content' => 'Return only valid JSON.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.2
            ]);

        if ($response->failed()) {
            throw new \RuntimeException('OpenAI request failed');
        }

        $content = $response->json('choices.0.message.content');

        if (!$content) {
            throw new \RuntimeException('OpenAI response content is empty');
        }

        return $content;
    }

    protected function normalizeRequirementsForPrompt(array $requirements = []): array
    {
        return array_map(function ($requirement) {
            $weight = isset($requirement['weight']) && $requirement['weight'] !== ''
                ? (int) $requirement['weight']
                : null;

            $priorityLabel = match ($weight) {
                5 => 'critical',
                3 => 'supportive',
                1 => 'secondary',
                default => 'unspecified',
            };

            return [
                'label' => $requirement['label'] ?? null,
                'value' => $requirement['value'] ?? null,
                'type' => $requirement['type'] ?? null,
                'required' => (bool)($requirement['required'] ?? false),
                'weight' => $weight,
                'priority_label' => $priorityLabel,
            ];
        }, $requirements);
    }



    protected function buildFailureResponse(string $reason): array
    {
        return [
            'success' => false,
            'is_cv' => false,
            'document_type' => 'other',
            'document_confidence' => 0,
            'rejection_reason' => $reason,
            'data' => [],
        ];
    }
}
