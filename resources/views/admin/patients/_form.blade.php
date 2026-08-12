@php
    $genderOptions = ['male' => 'Male', 'female' => 'Female', 'other' => 'Other'];
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <x-form.input label="Full Name" name="name" :value="$patient->name ?? ''" :required="true" />
    <x-form.input label="Phone Number" name="phone" type="tel" :value="$patient->phone ?? ''" :required="true" />
    <x-form.input label="Email Address" name="email" type="email" :value="$patient->email ?? ''" hint="Optional" />
    <x-form.select label="Gender" name="gender" :options="$genderOptions" :value="$patient->gender ?? ''" placeholder="Select gender" :required="true" />
</div>

<div class="mt-8 flex items-center gap-3">
    <x-btn type="submit" variant="primary">
        {{ isset($patient) ? 'Save Changes' : 'Add Patient' }}
    </x-btn>
    <x-btn :href="isset($patient) ? route('admin.patients.show', $patient) : route('admin.patients.index')" variant="ghost">
        Cancel
    </x-btn>
</div>
