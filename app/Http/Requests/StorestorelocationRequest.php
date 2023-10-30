<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorestorelocationRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'dzongkhag_id' => 'required|exists:dzongkhags,id', 
            'timezone_id' => 'required|exists:timezones,id', 
            'status' => 'required|boolean',
        ];
    }
}
