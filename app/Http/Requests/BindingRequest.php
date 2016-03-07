<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class BindingRequest extends Request
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
            "phone"=>"required|regex:/^1[34578][0-9]{9}$/",
            "valid"=>"required|digits:6",
            "password"=>"required|confirmed|min:6",
            "password_confirmation"=>"required|min:6",
        ];
    }

    public function messages()
    {
        return [
            "phone.required"=>"手机号必填",
            "phone.regex"=>"手机号格式错误",
            "valid.required"=>"验证码必填",
            "valid.digits"=>"验证码格式错误",
            "password.required"=>"密码必填",
            "password.confirmed"=>"两次密码不一致",
            "password.min"=>"密码最少6位",
            "password_confirmation.required"=>"确认密码必填",
            "password_confirmation.min"=>"确认密码最少6位",
        ];
    }
}
