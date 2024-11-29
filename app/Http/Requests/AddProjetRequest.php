<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddProjetRequest extends FormRequest
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
            'context' => ['required'],
            'code_reference' => ['required'],
            'cycle_de_gestion' => ['required'],
            'date_de_debut' => ['required', 'date'],
            'date_de_fin' => ['required', 'date'],
        ];
    }
    public function messages()
    {
        return [
            'code_reference.required' => 'Ce champ est obligatoire.',
            'cycle_de_gestion.required' => 'Ce champ est obligatoire.',
            'date_de_debut.required' => 'Ce champ est obligatoire.',
            'date_de_fin.required' => 'Ce champ nom obligatoire.',
            'cycle_de_gestion.integer' => 'Ce champ doit être un entier.',
        ];
    }
}
