<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerStoreRequest extends FormRequest
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
            'name'         => 'required',
            'phone'        => 'required|unique:customers,phone|max:10|min:10',
            'password'     => 'required|min:6',
            'office_name'  => 'required',
            'whatsapp'     => 'nullable|unique:customers,whatsapp|max:10|min:10',
            'kitchen_id'   => 'required',
            'city'         => 'required',
            'postal_code'   => 'required',
        ];
    }
}
