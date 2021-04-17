<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ItemInsertRequest extends FormRequest
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
     * @return arrayj
     */
    public function rules()
    {
        return [
			'name' => 'required|string|max:191',
			'content' => 'required|string|max:191',
			'price' => 'required|integer|digits_between:1,10|min:1',
			'quantity' => 'required|integer|digits_between:1,10|min:0',
			'image_name' => 'file|image|mimes:jpeg,png,gif|max:4096',
        ];
    }
}
