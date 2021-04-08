<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
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
			'title' => 'required|string|max:190',
			'type_id' => 'required|string|max:190',
			'content' => 'required|string|max:190',
			'name' => 'required|string|max:190',
			'email' => 'required|string|max:190',
			'valid_email' => 'same:email',
		];
	}
}
