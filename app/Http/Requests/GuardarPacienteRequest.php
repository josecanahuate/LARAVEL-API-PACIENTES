<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarPacienteRequest extends FormRequest
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
        $dniUniqueRule = $this->route('paciente')
        ? "unique:pacientes,dni," .$this->route('paciente')->id
        : "unique:pacientes,dni";

        return [
            "nombres" => ['required', 'max:100'],
            "apellidos" => ['required', 'max:150'],
            "edad" => "required",
            "sexo" => 'required',
            "dni" => ["required", $dniUniqueRule],
            "tipo_sangre" => "required",
            "correo" => "required",
            "direccion" => "required",
            "telefono" => "required",
        ];
    }
}
