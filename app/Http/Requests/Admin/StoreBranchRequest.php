<?php

namespace App\Http\Requests\Admin;

use App\Models\Branch;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-settings');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:30'],
            'status' => ['required', Rule::in([Branch::STATUS_OPEN, Branch::STATUS_COMING_SOON, Branch::STATUS_CLOSED])],
            'opening_hours' => ['nullable', 'string', 'max:500'],
            'map_link' => ['nullable', 'url', 'max:2048'],
        ];
    }
}
