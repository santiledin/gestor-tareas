<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|string|min:6',
            'confirm_password' => 'required|same:password'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'              => 'El nombre es obligatorio.',
            'name.string'                => 'El nombre debe ser una cadena de texto.',
            'name.max'                   => 'El nombre no debe exceder los 255 caracteres.',

            'email.required'             => 'El correo electrónico es obligatorio.',
            'email.email'                => 'Debe proporcionar un correo electrónico válido.',
            'email.unique'               => 'El correo electrónico ya está registrado.',

            'password.required'          => 'La contraseña es obligatoria.',
            'password.string'            => 'La contraseña debe ser una cadena de texto.',
            'password.min'               => 'La contraseña debe tener al menos 6 caracteres.',

            'confirm_password.required'  => 'La confirmación de la contraseña es obligatoria.',
            'confirm_password.same'      => 'Las contraseñas no coinciden.'
        ];
    }
}
