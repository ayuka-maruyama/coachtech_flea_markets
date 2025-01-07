<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'item_name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
            'condition' => 'required|string',
            'item_image' => 'required|file|image|mimes:jpeg,png',
            'category_ids' => 'required|array|min:1',
            'category_ids.*' => 'integer|exists:categories,category_id',
        ];
    }

    public function prepareForValidation()
    {
        $categoryIds = $this->input('category_id', ''); // カンマ区切り文字列として受け取る
        $this->merge([
            'price' => (int) str_replace([',', '￥'], '', $this->price),
            'category_ids' => $categoryIds ? json_decode($categoryIds) : [], // JSON文字列を配列に変換
        ]);
        logger('category_ids (after):', [$this->input('category_ids')]); // ログ出力
    }

    public function messages(): array
    {
        return [
            'item_name.required' => '商品名は必須です。',
            'price.required' => '価格を入力してください。',
            'price.numeric' => '価格は数値で入力してください。',
            'price.min' => '価格は0円以上で入力してください。',
            'category_ids.required' => 'カテゴリーを選択してください。',
            'category_ids.*.exists' => '選択したカテゴリーが無効です。',
        ];
    }
}
