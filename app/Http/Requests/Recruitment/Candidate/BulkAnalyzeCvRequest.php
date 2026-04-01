<?php

namespace App\Http\Requests\Recruitment\Candidate;

use Illuminate\Foundation\Http\FormRequest;

class BulkAnalyzeCvRequest extends FormRequest
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
            'cv_file_ids' => ['required', 'array'],
            'cv_file_ids.*' => ['required', 'integer', 'exists:candidate_cv_files,id'],
        ];
    }
}
