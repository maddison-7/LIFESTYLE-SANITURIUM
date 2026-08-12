@php
    $statusOptions = [
        'open' => 'Open',
        'coming_soon' => 'Coming Soon',
        'closed' => 'Closed',
    ];
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <x-form.input label="Branch Name" name="name" :value="$branch->name ?? ''" :required="true" />
    <x-form.input label="Phone" name="phone" :value="$branch->phone ?? ''" />

    <div class="sm:col-span-2">
        <x-form.input label="Location / Address" name="location" :value="$branch->location ?? ''" :required="true" />
    </div>

    <x-form.select label="Status" name="status" :options="$statusOptions" :value="$branch->status ?? 'coming_soon'" :required="true" />
    <x-form.input label="Map Link" name="map_link" :value="$branch->map_link ?? ''" hint="Google Maps embed or share URL." />

    <div class="sm:col-span-2">
        <x-form.textarea label="Opening Hours" name="opening_hours" :value="$branch->opening_hours ?? ''" :rows="3" hint="Leave blank if not yet finalised." />
    </div>
</div>

<div class="mt-8 flex items-center gap-3">
    <x-btn type="submit" variant="primary">
        {{ isset($branch) ? 'Save Changes' : 'Create Branch' }}
    </x-btn>
    <x-btn :href="route('admin.branches.index')" variant="ghost">
        Cancel
    </x-btn>
</div>
