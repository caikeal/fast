<?php

namespace App\Http\Requests;

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
            'account'=>'required|max:255',
            'password'=>'required|min:6'
        ];
    }

    public function messages()
    {
        return [
            'account.required'=>'账户必填',
            'account.max'=>'账户格式不正确',
            'password.required'=>'密码必填',
            'password.min'=>'密码格式不正确',
        ];
    }
}
