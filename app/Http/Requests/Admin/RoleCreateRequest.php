<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class RoleCreateRequest extends Request
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
            'name' => 'required|unique:roles,name',
            'label' => 'required|unique:roles,label',
            'level' => 'required|in:1,2,3',
            'relate' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '英文名称必填！',
            'name.unique' => '英文名称已经存在！',
            'label.required' => '名称必填！',
            'label.unique' => '名称已存在！',
            'level.required' => '权限等级必填！',
            'level.in' => '权限等级格式错误！',
            'relate.required' => '父级权限必填！',
        ];
    }
}
