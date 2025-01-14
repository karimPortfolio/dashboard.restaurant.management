<?php

namespace App\Http\Requests;

use App\Enums\EmployeePosition;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('employees')->ignore($this->employee),
            ],
            'phone'=> 'required|string|max:255',
            'salary'=> 'required|numeric',
            'status'=> 'required|string|max:255',
            'cnss_number'=> 'required|string|max:255',
            'cin_number'=> 'required|string|max:255',
            'joining_date'=> 'required|date',
            'position'=> [
                'required',
                'string',
                'max:255',
                Rule::enum(EmployeePosition::class),
            ],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'position' => $this->input('position.value'),
            'status'=> $this->input('status.value'),
        ]);
    }
}
