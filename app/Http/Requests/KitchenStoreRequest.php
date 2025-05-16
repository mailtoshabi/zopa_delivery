<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KitchenStoreRequest extends FormRequest
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
            'name' => 'required',
            'phone' => 'required|string|max:10|min:10|unique:kitchens,phone',
            'whatsapp' => 'nullable|string|max:10|min:10|unique:kitchens,whatsapp',
            'city' => 'required',
            'district_id' => 'required',
            'state_id' => 'required',
        ];
    }
}
