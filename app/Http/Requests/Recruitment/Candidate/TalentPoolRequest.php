<?php

namespace App\Http\Requests\Recruitment\Candidate;

use Illuminate\Foundation\Http\FormRequest;

class TalentPoolRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'candidate_id' => ['required', 'integer', 'exists:candidates,id'],
            'source_application_id' => ['required', 'integer', 'exists:candidate_applications,id'],
            'source_vacancy_id' => ['required', 'integer', 'exists:vacancies,id'],
            'category' => ['required', 'string', 'in:recommended,watchlist,future_fit'],
            'note' => ['nullable', 'string'],
        ];
    }
}
