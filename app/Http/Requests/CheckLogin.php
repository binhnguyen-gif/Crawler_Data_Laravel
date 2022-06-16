<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckLogin extends FormRequest
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
            'name' => 'required|min:3|max:12',
            'password' => 'required|min:3'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => ':attribute không được để trống',
            'name.min' => ':attribute không phải chứa ít nhất 3 kí tự',
            'name.max' => ':attribute không được chứa không quá 12 kí tự',
            'password.required' => ':attribute không được để trống',
            'password.min' => ':attribute phải chứa ít nhất 3 kí tự',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Tên đăng nhập',
            'password' => 'Mật khẩu',
        ];
    }

}
