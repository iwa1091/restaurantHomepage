<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        // ※ 管理者ガード名が確定している場合は、ここで認可を厳密化できます。
        // return auth('admin')->check();
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'image'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:512'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'        => '商品名',
            'description' => '商品説明',
            'price'       => '価格',
            'stock'       => '在庫数',
            'image'       => '商品画像',
        ];
    }

    public function messages(): array
    {
        return [
            'image.image' => '商品画像には画像ファイルを選択してください。',
            'image.mimes' => '商品画像はjpeg・png・jpg・gif・webp形式のみ対応しています。',
            'image.max'   => '商品画像は500KB以内のファイルを選択してください。',
        ];
    }
}
