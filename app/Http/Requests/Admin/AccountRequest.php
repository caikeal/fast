<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class AccountRequest extends Request
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
            'name'=>'required',
            'account'=>'required|email|unique:managers,email',
            'pwd'=>'required|min:6',
            'role'=>'required|array'
        ];
    }

    public function messages()
    {
        return [
            'name.required'=>'姓名必填！',
            'account.required'=>'登录账户必填！',
            'account.email'=>'登录账户必为邮箱！',
            'account.unique'=>'登录账户已经存在！',
            'pwd.required'=>'初始密码必填！',
            'pwd.min'=>'初始密码至少6位！',
            'role.required'=>'权限必选！',
            'role.array'=>'权限格式错误！'
        ];
    }
}
