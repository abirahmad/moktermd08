<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'full_name' => 'required|full_name',
            'address' => 'required',
            'email' => 'required',
        ];
    }

    /**
     * Get the messages for the rules
     *
     * @return array
     */
    public function messages()
    {
        return [
            'full_name.required' => 'Full Name Required',
            'email.email' => 'Email Required',
            'address.required' => 'Address Required',
        ];
    }
}
