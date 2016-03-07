<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class SalaryBaseRequest extends Request
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
            'cid'=>'required|integer',
            'category'=>'required|array',
            'title'=>'required'
        ];
    }

    public function messages()
    {
        return [
            'cid.required'=>'格式错误',
            'cid.integer'=>'格式错误',
            'category.required'=>'类型必须选择',
            'category.array'=>'类型格式错误',
            'title.required'=>'模版标题必填'
        ];
    }
}
