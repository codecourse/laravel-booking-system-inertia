<?php

namespace App\Http\Requests;

use App\Models\Employee;
use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AppointmentStoreRequest extends FormRequest
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
            'employee_id' => ['required', Rule::exists(Employee::class, 'id')],
            'service_id' => ['required', Rule::exists(Service::class, 'id')],
            'name' => ['required'],
            'email' => ['required', 'email'],
            'datetime' => ['required', 'date_format:Y-m-d H:i:s']
        ];
    }
}
