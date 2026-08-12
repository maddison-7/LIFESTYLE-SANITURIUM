@php
    $statusOptions = ['active' => 'Active', 'inactive' => 'Inactive'];
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <x-form.input label="Product Name" name="name" :value="$medicine->name ?? ''" :required="true" />

    <div>
        <label for="field-category" class="block text-sm font-medium text-gray-700 mb-1.5">
            Category <span class="text-red-500">*</span>
        </label>
        <input
            id="field-category"
            type="text"
            name="category"
            list="category-suggestions"
            value="{{ old('category', $medicine->category ?? '') }}"
            required
            class="w-full rounded-lg border px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-base {{ $errors->has('category') ? 'border-red-300' : 'border-surface-300' }}"
        >
        <datalist id="category-suggestions">
            @foreach ($categories ?? [] as $category)
                <option value="{{ $category }}"></option>
            @endforeach
        </datalist>
        @error('category') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    @unless (isset($medicine))
        <x-form.input label="Opening Stock Quantity" name="quantity" type="number" min="0" :value="0" :required="true" hint="Recorded automatically as an opening Stock In transaction." />
    @endunless

    <x-form.input label="Unit Price (TZS)" name="unit_price" type="number" step="0.01" min="0" :value="$medicine->unit_price ?? ''" />
    <x-form.input label="Expiry Date" name="expiry_date" type="date" :value="$medicine?->expiry_date?->toDateString()" />
    <x-form.select label="Status" name="status" :options="$statusOptions" :value="$medicine->status ?? 'active'" :required="true" />

    <div class="sm:col-span-2">
        <x-form.textarea label="Description" name="description" :value="$medicine->description ?? ''" :rows="4" />
    </div>
</div>

<div class="mt-8 flex items-center gap-3">
    <x-btn type="submit" variant="primary">
        {{ isset($medicine) ? 'Save Changes' : 'Add Medicine' }}
    </x-btn>
    <x-btn :href="isset($medicine) ? route('admin.medicines.show', $medicine) : route('admin.medicines.index')" variant="ghost">
        Cancel
    </x-btn>
</div>
