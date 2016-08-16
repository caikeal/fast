<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class ManagerRoleUpdateRequest extends Request
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
            'account'=>'email|unique:managers,email'
        ];
    }

    public function messages()
    {
        return [
            'account.email'=>'邮箱格式错误！',
            'account.unique'=>'邮箱已经存在！'
        ];
    }
}
