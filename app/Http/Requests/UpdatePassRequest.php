<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePassRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'password' => 'required|min:3|max:20',
            'password_confirmation' => 'required|min:3|max:20|same:password',
        ];
    }

    function getPUTPreset()
    {
        return [
            'name' => 'required|string|max:255',
            'password' => 'required|min:3|max:20',
            'password_confirmation' => 'required|min:3|max:20|same:password',
        ];
    }


}
