<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'profile_name' => 'required|string|max:255',
            'postal_number' => ['required', 'string', 'regex:/^\d{7}$|^\d{3}-\d{4}$/'],
            'address' => 'required|string|max:255',
            'building' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'profile_name.required' => 'ユーザー名を入力してください',
            'postal_number.required' => '郵便番号を入力してください',
            'postal_number.regex' => '郵便番号は「1234567」の形式で入力してください',
            'address.required' => '住所を入力してください',
            'building.required' => '建物名を入力してください',
        ];
    }
}
