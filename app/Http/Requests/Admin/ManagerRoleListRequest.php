<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class ManagerRoleListRequest extends Request
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
            'role_id'=>'required',
        ];
    }

    public function messages()
    {
        return [
            'role_id.required' => '必填！'
        ];
    }
}
