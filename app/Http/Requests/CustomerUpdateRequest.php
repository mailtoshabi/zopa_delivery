<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerUpdateRequest extends FormRequest
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
        $id = decrypt($this->route('customer'));

        return [
            'name'        => 'required|string',
            'phone'       => 'required|string|max:10|min:10|unique:customers,phone,' . $id,
            'whatsapp'    => 'nullable|string|max:10|min:10|unique:customers,whatsapp,' . $id,
            // You can add other fields like 'office_name', etc., as needed
        ];
    }
}
