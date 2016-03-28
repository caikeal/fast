<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class SalaryTaskRequest extends Request
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
            'receiver'=>'required',
            'sd'=>'required_without:id|date',
            'id'=>'required_without:sd|date'
        ];
    }

    public function messages()
    {
        return [
            'name.required'=>'企业名必填！',
            'receiver.required'=>'分配用户必填！',
            'sd.required_without'=>'薪资和社保两者必填1项！',
            'id.required_without'=>'薪资和社保两者必填1项！',
            'sd.date'=>'时间格式错误！',
            'id.date'=>'时间格式错误！'
        ];
    }
}
