<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required','string'],
            'email' =>['required','email','unique:users,email'],
            'password' =>['required','confirmed','min:8'],
        ];
    }

    public function messages(){
        return [
        'name.required' => 'お名前を入力してください',
        'email.required' => 'メールアドレスを入力してください',
        'email.email' =>'正しいメールアドレスで入力してください',
        'email.unique' => 'このメールアドレスは既に使用されています',
        'password.required' =>'パスワードを入力してください',
        'password.confirmed' =>'パスワードと一致しません',
        'password.min' =>'パスワードは８文字以上で入力してください',
        ];
    }
}
