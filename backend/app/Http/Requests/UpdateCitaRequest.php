<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCitaRequest extends FormRequest
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
            'paciente_id' => ['sometimes', 'integer', 'exists:pacientes,id'],
            'doctor_id' => ['sometimes', 'integer', 'exists:doctores,id'],
            'fecha_inicio' => ['sometimes', 'required', 'date'],
            'fecha_fin' => ['sometimes', 'required', 'date', 'after:fecha_inicio'],
            'motivo' => ['sometimes', 'required', 'string', 'max:255'],
        ];
    }
}
