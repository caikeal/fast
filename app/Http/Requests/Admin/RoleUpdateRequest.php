<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class RoleUpdateRequest extends Request
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
            'id' => 'required',
            'label' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'id.required' => '格式错误！',
            'label.required' => '缺少参数！'
        ];
    }
}
