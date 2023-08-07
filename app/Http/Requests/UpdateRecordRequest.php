<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules()
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'release_year' => 'sometimes|required|integer|min:1900|max:' . ((int)date('Y') + 2),
            'imdb_id' => 'sometimes|required|string|max:255',
            'images' => 'sometimes|nullable|string'
        ];
    }
}
