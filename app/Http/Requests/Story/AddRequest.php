<?php

namespace App\Http\Requests\Story;

use Illuminate\Foundation\Http\FormRequest;

class AddRequest extends FormRequest
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
            'title' => 'required',
            'link' => 'required|url',
            'description' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => ':attribute không được để trống',
            'link.required' => ':attribute không được để trống',
            'link.url' => ':attribute phải là url',
            'description.required' => ':attribute không được để trống',
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'Tên truyện',
            'link' => 'URL ',
            'description' => 'Miêu tả',
        ];
    }
}
