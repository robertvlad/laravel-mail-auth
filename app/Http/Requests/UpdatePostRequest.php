<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => ['required', Rule::unique('posts')->ignore($this->post), 'max:150'],
            'content' => ['nullable'],
            'type_id' => ['nullable', 'exists:types,id'],
            'technologies' => ['exists:technologies,id'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     * 
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'Il titolo è richesto',
            'title.unique' => 'E\' gia presente un post con questo titolo',
            'title.max' => 'Il titolo deve essere inferiore ai :max caratteri',
            'type_id.exists' => 'Seleziona una tipologia valida',
            'technologies.exists' => 'Seleziona una technology valida',
            'cover_image.image' => 'inserire un formato di immagine valido',
        ];
    }
}
