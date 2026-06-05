<?php

namespace App\Http\Requests\Aed;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 「このリクエスト（処理）を実行しようとしているユーザーは、本当にその権限を持っていますか？」をチェックする場所
     */
    public function authorize(): bool
    {
        // ログインしていて、かつユーザロールが管理者だったら通す
        return auth()->check() && auth()->user()->role == 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:1', 'max:100'],
            'postcode' => ['required', 'string', 'regex:/^[0-9]{7}$/'],
            'prefecture' => ['required', 'string', 'max:10'],
            'municipality' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '施設名は必ず入力してください。',
            'postcode' => '郵便番号はハイフンなしの7桁で入力してください。',
            'prefecture' => '県名は必ず入力してください',
            'municipality' => '市町村名は必ず入力してください',
            'address' => '番地は必ず入力してください',
            'description' => '文字数制限は255字です。',
            'latitude' => '緯度は-90~90（北緯90度~南緯90度）の範囲で指定してください',
            'longitude' => '経度は-180~180（西経180度~東経180度）の範囲で指定してください',
        ];
    }    
}
