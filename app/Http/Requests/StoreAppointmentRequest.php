<?php

namespace App\Http\Requests;

use App\Models\Branch;
use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^[0-9+\-\s()]{7,20}$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'gender' => ['required', Rule::in(['male', 'female', 'other'])],
            'service_id' => ['required', Rule::exists('services', 'id')->where('status', Service::STATUS_ACTIVE)],
            'branch_id' => ['required', Rule::exists('branches', 'id')->where(fn ($query) => $query->where('status', '!=', Branch::STATUS_CLOSED))],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_time' => ['required', 'date_format:H:i'],
            'message' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Please enter a valid phone number.',
            'appointment_date.after_or_equal' => 'Please choose today or a future date.',
        ];
    }

    public function attributes(): array
    {
        return [
            'service_id' => 'service',
            'branch_id' => 'branch',
            'full_name' => 'full name',
            'appointment_date' => 'appointment date',
            'appointment_time' => 'appointment time',
        ];
    }
}
