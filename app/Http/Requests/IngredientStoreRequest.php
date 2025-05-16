<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IngredientStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adjust if using authorization logic
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:ingredients,name',
            'status' => 'required|boolean',
        ];
    }
}
