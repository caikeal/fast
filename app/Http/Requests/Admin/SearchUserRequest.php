<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class SearchUserRequest extends Request
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
            'phone'=>'required|digits_between:8,16'
        ];
    }

    public function messages()
    {
        return [
            'phone.required'=>'手机号必填！',
            'phone.digits_between'=>'手机号格式错误！',
        ];
    }
}
