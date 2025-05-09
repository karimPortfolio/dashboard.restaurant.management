<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'parent_category' => 'nullable|integer|exists:categories,id',
            'image' => 'nullable|image|max:2048|mimes:jpeg,jpg,png',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'parent_category' => $this->input('parent_category.id'),
        ]);
    }
}
