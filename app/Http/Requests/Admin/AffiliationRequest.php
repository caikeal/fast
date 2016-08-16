<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class AffiliationRequest extends Request
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
            'level' => 'required|in:1,2,3'
        ];
    }

    public function messages()
    {
        return [
            'level.required' => '等级必填',
            'level.in' => '等级格式错误'
        ];
    }
}
