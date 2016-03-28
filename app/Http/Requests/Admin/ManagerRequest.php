<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class ManagerRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pwd'=>'confirmed|required',
            'pwd_confirmation'=>'required_with:pwd'
        ];
    }

    public function messages()
    {
        return [
            'pwd.required'=>'新密码必填！',
            'pwd.confirmed'=>'两次密码不一致！',
            'pwd_confirmation.required_with'=>'缺少参数！'
        ];
    }
}
