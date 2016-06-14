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
            'email'=>'email|unique:managers,email,'.$this->get('user_id'),
            'phone'=>'numeric|min:6'
        ];
    }

    public function messages()
    {
        return [
            'email.email'=>'邮箱格式错误！',
            'email.unique'=>'该邮箱已存在！',
            'phone.numeric'=>'电话号码格式错误！',
            'phone.min'=>'电话号码位数错误！'
        ];
    }
}
