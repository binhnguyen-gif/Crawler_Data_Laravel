<?php

namespace App\Http\Requests\Chapter;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'description' => 'required',
            'stories_id' => 'required',
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
            'title' => 'Tên chương',
            'link' => 'URL ',
            'description' => 'Miêu tả',
        ];
    }
}
