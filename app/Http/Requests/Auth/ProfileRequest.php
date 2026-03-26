<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'image' => ['nullable', 'image', 'mimes:png,jpg,svg,webp', 'max:2048'],
            'email' => ['required', 'string', 'max:255', 'unique:users,email,' . auth()->id()],
            'name' => ['required', 'string', 'max:255', 'unique:users,name,' . auth()->id()],
            'new_password' => ['nullable', 'min:6'],
            'repeat_password' => ['nullable', 'min:6', 'same:new_password'],
        ];
    }
}
