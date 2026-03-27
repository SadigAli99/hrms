<?php

namespace App\Http\Requests\Recruitment\Vacancy;

use Illuminate\Foundation\Http\FormRequest;

class VacancyRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:500'],
            'employment_type' => ['required', 'string', 'max:30'],
            'work_mode' => ['required', 'string', 'max:30'],
            'seniority_level' => ['required', 'string', 'max:30'],
            'min_salary' => ['nullable', 'numeric', 'min:0'],
            'max_salary' => ['nullable', 'numeric', 'min:0'],
            'location' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'requirements_text' => ['nullable', 'string'],
            'status' => ['required', 'string', 'max:50'],
            'closed_at' => ['nullable', 'date'],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'vacancy_requirements' => ['nullable', 'array'],
            'vacancy_requirements.*.label' => ['required', 'string', 'max:255'],
            'vacancy_requirements.*.value' => ['nullable', 'string', 'max:255'],
            'vacancy_requirements.*.type' => ['required', 'string', 'in:skill,experience,language,education,tool,certification'],
            'vacancy_requirements.*.required' => ['required', 'in:0,1'],
        ];
    }
}
