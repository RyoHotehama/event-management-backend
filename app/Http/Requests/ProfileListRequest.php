<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileListRequest extends BaseRequest
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
            'page' => 'nullable|integer',
            'search' => 'nullable|string',
            'role' => 'nullable|integer'
        ];
    }

    public function messages(): array
    {
        return [
            'page.integer' => 'pageを正しい形式で入力してください。',
            'search.number' => 'searchを正しい形式で入力してください。',
            'role.integer' => 'roleを正しい形式で入力してください。',
        ];
    }
}
