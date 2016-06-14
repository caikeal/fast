<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class ManagerPasswordResetRequest extends Request
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
            'old_pwd'=>'required|min:6',
            'pwd'=>'confirmed|required|min:6',
            'pwd_confirmation'=>'required_with:pwd'
        ];
    }

    public function messages()
    {
        return [
            'old_pwd.required'=>'原密码必填！',
            'old_pwd.min'=>'原密码至少6位！',
            'pwd.required'=>'新密码必填！',
            'pwd.min'=>'新密码至少6位！',
            'pwd.confirmed'=>'两次密码不一致！',
            'pwd_confirmation.required_with'=>'缺少参数！'
        ];
    }
}
