<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'profile_image' => 'file|image|mimes:jpeg,png',
        ];
    }

    public function messages(): array
    {
        return [
            'profile_image.file' => 'プロフィールで使用するファイルを選択してください',
            'profile_image.image' => '画像ファイルを選択してください',
            'profile_image.mimes' => '画像ファイルはjpegかpngのファイルを選択してください',
        ];
    }
}
