@php
    $statusOptions = ['active' => 'Active', 'inactive' => 'Inactive'];
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <x-form.input label="Service Name" name="name" :value="$service->name ?? ''" :required="true" />

    <div>
        <label for="field-category" class="block text-sm font-medium text-gray-700 mb-1.5">
            Category <span class="text-red-500">*</span>
        </label>
        <input
            id="field-category"
            type="text"
            name="category"
            list="category-suggestions"
            value="{{ old('category', $service->category ?? '') }}"
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

    <div class="sm:col-span-2">
        <x-form.textarea label="Description" name="description" :value="$service->description ?? ''" :rows="4" />
    </div>

    <x-form.select label="Status" name="status" :options="$statusOptions" :value="$service->status ?? 'active'" :required="true" />

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Image</label>
        @if (!empty($service?->image))
            <img src="{{ $service->imageUrl() }}" alt="{{ $service->name }}" class="h-16 w-16 rounded-lg object-cover mb-3">
        @endif
        <input type="file" name="image" accept="image/*" class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-primary-700 hover:file:bg-primary-100">
        @error('image') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-8 flex items-center gap-3">
    <x-btn type="submit" variant="primary">
        {{ isset($service) ? 'Save Changes' : 'Create Service' }}
    </x-btn>
    <x-btn :href="route('admin.services.index')" variant="ghost">
        Cancel
    </x-btn>
</div>
