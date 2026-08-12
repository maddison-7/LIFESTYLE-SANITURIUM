<?php

namespace App\Http\Requests\Admin;

use App\Models\InventoryTransaction;
use App\Models\Medicine;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreInventoryTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-settings');
    }

    public function rules(): array
    {
        return [
            'medicine_id' => ['required', Rule::exists('medicines', 'id')],
            'type' => ['required', Rule::in([InventoryTransaction::TYPE_IN, InventoryTransaction::TYPE_OUT])],
            'quantity' => ['required', 'integer', 'min:1'],
            'reference' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($this->input('type') !== InventoryTransaction::TYPE_OUT) {
                return;
            }

            $medicine = Medicine::find($this->input('medicine_id'));

            if ($medicine && $this->integer('quantity') > $medicine->quantity) {
                $validator->errors()->add('quantity', "Only {$medicine->quantity} units of {$medicine->name} are currently in stock.");
            }
        });
    }
}
