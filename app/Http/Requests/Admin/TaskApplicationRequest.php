<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class TaskApplicationRequest extends Request
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
            'upload_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'upload_id.required' => '缺少参数！'
        ];
    }
}
