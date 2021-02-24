<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
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
			'name' => [
				'required',
				'string',
				'max:191',
				Rule::unique('items')->ignore(session()->get('admin_item_id')),
			],
			'content' => 'required|string|max:191',
			'price' => 'required|integer|digits_between:1, 10|min:1',
			'quantity' => 'required|integer|digits_between:1, 10|min:0',
        ];
    }
}
