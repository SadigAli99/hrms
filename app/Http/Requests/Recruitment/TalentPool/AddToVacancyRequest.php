<?php

namespace App\Http\Requests\Recruitment\TalentPool;

use Illuminate\Foundation\Http\FormRequest;

class AddToVacancyRequest extends FormRequest
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
            'source_vacancy_id' => ['required', 'integer', 'exists:vacancies,id'],
            'vacancy_id' => ['required', 'integer', 'exists:vacancies,id'],
            'note' => ['nullable', 'string'],
        ];
    }
}
