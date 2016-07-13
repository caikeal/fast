<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class NewsAllowRequest extends Request
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
            'st'=>'required'
        ];
    }

    public function messages()
    {
        return [
            'st.required'=>'状态必填！'
        ];
    }
}
