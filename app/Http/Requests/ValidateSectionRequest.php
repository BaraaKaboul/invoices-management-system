<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateSectionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'section_name' => 'required|unique:sections'
        ];

    }
    public function messages(){

        return [
            'section_name.required' => 'يرجى ملئ "اسم القسم" بالمعلومات المطلوبة',
            'section_name.unique' => 'هذا القسم موجود مسبقا',
        ];
    }



}
