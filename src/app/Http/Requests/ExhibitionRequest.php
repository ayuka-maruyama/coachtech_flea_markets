<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        $categoryIds = $this->input('category_id', '');

        $this->merge([
            'price' => $this->price !== null ? (int) str_replace([',', '￥'], '', $this->price) : null,
            'category_ids' => $categoryIds ? json_decode($categoryIds) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'item_name' => 'required|string',
            'brand' => 'required|string',
            'price' => 'required|numeric|min:1',
            'description' => 'required|string|max:255',
            'condition' => 'required|string',
            'item_image' => 'required|file|image|mimes:jpeg,png',
            'category_ids' => 'required|array|min:1',
            'category_ids.*' => 'integer|exists:categories,category_id',
        ];
    }

    public function messages(): array
    {
        return [
            'item_name.required' => '商品名を入力してください。',
            'brand.required' => 'ブランド名を入力してください。',
            'brand.string' => 'ブランド名は文字列で入力してください。',
            'price.required' => '価格を入力してください。',
            'price.numeric' => '価格は数値で入力してください。',
            'price.min' => '価格は0円以上で入力してください。',
            'description.required' => '商品の説明を入力してください。',
            'description.string' => '商品の説明は文字列で入力してください。',
            'description.max' => '商品の説明は255文字以内で入力してください。',
            'condition.required' => '商品状態を選択してください。',
            'item_image.required' => '商品画像を選択してください。',
            'item_image.mimes' => '商品画像はjpegかpngのファイルをしてください。',
            'category_ids.required' => 'カテゴリーを選択してください。',
            'category_ids.*.exists' => '選択したカテゴリーが無効です。',
        ];
    }
}
