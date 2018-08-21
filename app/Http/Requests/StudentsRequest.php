<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentsRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'faculty' => 'required',
            'year_of_study' => 'required',
            'enrollment_year' => 'required|numeric',
            'graduation_year' => 'required|numeric',
        ];
    }
}
