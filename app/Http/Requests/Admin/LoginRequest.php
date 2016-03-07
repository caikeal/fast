<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class LoginRequest extends Request
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
            'email'=>'required|email|max:255',
            'password'=>'required|min:6'
        ];
    }

    public function messages()
    {
        return [
            'email.required'=>'账户必填',
            'email.email'=>'邮箱格式不正确',
            'email.max'=>'账户格式不正确',
            'password.required'=>'密码必填',
            'password.min'=>'密码格式不正确',
        ];
    }
}
