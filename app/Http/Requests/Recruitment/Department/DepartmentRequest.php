<?php

namespace App\Http\Requests\Recruitment\Department;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:departments,name,' . $this->route('department')],
            'status' => ['required', 'in:0,1'],
            'parent_id' => ['nullable', 'integer', 'exists:departments,id'],
            'manager_user_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
}
