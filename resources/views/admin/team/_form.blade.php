@php
    $statusOptions = ['active' => 'Active', 'inactive' => 'Inactive'];
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <x-form.input label="Full Name" name="name" :value="$member->name ?? ''" :required="true" />
    <x-form.input label="Professional Title" name="title" :value="$member->title ?? ''" :required="true" hint="e.g. Clinical Officer, Nurse, Counsellor." />
    <x-form.input label="Specialty" name="specialty" :value="$member->specialty ?? ''" />
    <x-form.select label="Status" name="status" :options="$statusOptions" :value="$member->status ?? 'active'" :required="true" />

    <div class="sm:col-span-2">
        <x-form.textarea label="Biography" name="biography" :value="$member->biography ?? ''" :rows="4" />
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Profile Photo</label>
        @if (!empty($member?->photo))
            <img src="{{ $member->photoUrl() }}" alt="{{ $member->name }}" class="h-16 w-16 rounded-full object-cover mb-3">
        @endif
        <input type="file" name="photo" accept="image/*" class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-primary-700 hover:file:bg-primary-100">
        @error('photo') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-8 flex items-center gap-3">
    <x-btn type="submit" variant="primary">
        {{ isset($member) ? 'Save Changes' : 'Add Team Member' }}
    </x-btn>
    <x-btn :href="route('admin.team.index')" variant="ghost">
        Cancel
    </x-btn>
</div>
