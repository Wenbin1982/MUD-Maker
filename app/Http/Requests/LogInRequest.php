<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LogInRequest extends FormRequest
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

    public function rules()
    {
        return $this->method() === 'POST'
            ? $this->getPOSTPreset()
            : $this->getPUTPreset();
    }

    function getPOSTPreset()
    {

        return [
            'email' => 'required|exists:users|email|max:255',
            'password' => 'required|string|min:3|max:20',
        ];
    }

    function getPUTPreset()
    {
        return [
            'email' => 'required|exists:users|email|max:255',
            'password' => 'required|string|min:3|max:20',
        ];
    }


}
