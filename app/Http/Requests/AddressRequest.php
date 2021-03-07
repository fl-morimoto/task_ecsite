<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
           'zip' => 'required|string|max:7',
           'state' => 'required|string|max:5',
           'city' => 'required|string|max:20',
           'street' => 'required|string|max:20',
           'tel' => 'required|regex:/(0)[0-9]{10}/|digits_between:10, 11',
        ];
    }
}
