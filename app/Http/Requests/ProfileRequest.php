<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends BaseRequest
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
            'email' => 'required|string|email|unique:users,email',
            'role' => 'required|integer',
            'lastName' => 'required|string',
            'firstName' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'メールアドレスは必須です。',
            'email.string' => 'メールアドレスを正しい形式で入力してください。',
            'email.email' => 'メールアドレスを正しい形式で入力してください。',
            'email.unique' => 'このメールアドレスはすでに存在します',
            'role.required' => 'roleは必須です。',
            'role.integer' => 'roleを正しい形式で入力してください。',
            'lastName.required' => '性は必須です。',
            'lastName.string' => '性を正しい形式で入力してください。',
            'firstName.required' => '名は必須です。',
            'firstName.string' => '名を正しい形式で入力してください。',
        ];
    }
}
