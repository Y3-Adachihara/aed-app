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
            'postcode.required' => '郵便番号は必ず入力してください。',
            'postcode.regex' => '郵便番号はハイフンなしの7桁で入力してください。',
            'prefecture.required' => '県名は必ず入力してください。',
            'municipality.required' => '市町村名は必ず入力してください。',
            'address.required' => '番地は必ず入力してください。',
            'description.max' => '説明文は255文字以内で入力してください。',
            'latitude.required' => '緯度は必ず入力してください。',
            'latitude.between' => '緯度は-90〜90の範囲で指定してください。',
            'longitude.required' => '経度は必ず入力してください。',
            'longitude.between' => '経度は-180〜180の範囲で指定してください。',
        ];
    }

    public function aedId(): int {
        return (int) $this->route('aedId');
    }

    public function name(): string {
        return $this->input('name');
    }

    public function postcode(): string {
        return $this->input('postcode');
    }

    public function prefecture(): string {
        return $this->input('prefecture');
    }

    public function municipality(): string {
        return $this->input('municipality');
    }

    public function address(): string {
        return $this->input('address');
    }

    public function description(): string {
        
        return $this->filled('description') ? $this->input('description'): '';
    }

    public function latitude(): float {
        return (float) $this->input('latitude');
    }

    public function longitude(): float {
        return (float) $this->input('longitude');
    }

}
