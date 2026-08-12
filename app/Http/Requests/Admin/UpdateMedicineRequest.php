<?php

namespace App\Http\Requests\Admin;

use App\Models\Medicine;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMedicineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-settings');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'unit_price' => ['nullable', 'numeric', 'min:0', 'max:99999999.99'],
            'expiry_date' => ['nullable', 'date'],
            'status' => ['required', Rule::in([Medicine::STATUS_ACTIVE, Medicine::STATUS_INACTIVE])],
        ];
    }
}
