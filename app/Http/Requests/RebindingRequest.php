<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class RebindingRequest extends Request
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
            'valid'=>'required|digits:6',
            'newPhone'=>'required|regex:/^1[34578][0-9]{9}$/',
            'newValid'=>'required|digits:6',
        ];
    }

    public function messages()
    {
        return [
            'valid.required'=>'原手机号验证码必填',
            'valid.digits'=>'原手机号验证码格式不正确',
            'newPhone.required'=>'现手机号必填',
            'newPhone.regex'=>'现手机号格式不正确',
            'newValid.required'=>'现手机号验证码必填',
            'newValid.digits'=>'现手机号验证码格式不正确',
        ];
    }
}
