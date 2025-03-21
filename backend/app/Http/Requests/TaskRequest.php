<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pendiente,completa',
            'priority' => 'required|in:baja,media,alta',
            'due_date' => 'nullable|date',            
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'El título es obligatorio.',
            'title.string' => 'El título debe ser una cadena de texto.',
            'title.max' => 'El título no puede exceder los 255 caracteres.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado debe ser uno de los siguientes: pendiente, completada.',
            'priority.required' => 'La prioridad es obligatorio.',
            'priority.in' => 'La prioridad debe ser uno de los siguientes: baja, media o alta.',
            'due_date.date' => 'La fecha de vencimiento debe ser una fecha válida.',
        ];
    }
}
