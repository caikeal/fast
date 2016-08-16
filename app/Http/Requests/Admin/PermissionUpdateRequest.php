<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class PermissionUpdateRequest extends Request
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
            'permissions' => 'required|array'
        ];
    }

    public function messages()
    {
        return [
            'id.required' => '必填',
            'permissions.required' => '必填',
            'permissions.array' => '格式错误'
        ];
    }
}
