<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method' => 'required|string|max:255',
            'postal_number' => 'required|string|regex:/^(\d{3})-(\d{4})$/',
            'address' => 'required|string|max:255',
            'building' => 'required|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'payment_method.required' => '支払方法を選択してください',
            'postal_number.required' => '郵便番号を入力してください',
            'postal_number.regex' => '郵便番号は「123-4567」の形式で入力してください',
            'address.required' => '住所を入力してください',
            'building.required' => '建物名を入力してください',
        ];
    }
}
