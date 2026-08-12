@php
    $roleOptions = [
        'super_admin' => 'Super Admin',
        'clinic_admin' => 'Clinic Admin',
        'receptionist' => 'Receptionist',
        'healthcare_staff' => 'Healthcare Staff',
    ];
    $statusOptions = [
        'active' => 'Active',
        'inactive' => 'Inactive',
    ];
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <x-form.input label="Full Name" name="name" :value="$editUser->name ?? ''" :required="true" />
    <x-form.input label="Email Address" name="email" type="email" :value="$editUser->email ?? ''" :required="true" autocomplete="off" />

    <x-form.input
        :label="isset($editUser) ? 'New Password' : 'Password'"
        name="password"
        type="password"
        :required="!isset($editUser)"
        :hint="isset($editUser) ? 'Leave blank to keep the current password.' : 'Minimum 8 characters.'"
        autocomplete="new-password"
    />
    <x-form.input
        :label="isset($editUser) ? 'Confirm New Password' : 'Confirm Password'"
        name="password_confirmation"
        type="password"
        :required="!isset($editUser)"
        autocomplete="new-password"
    />

    <x-form.select label="Role" name="role" :options="$roleOptions" :value="$editUser->role ?? 'receptionist'" :required="true" />
    <x-form.select label="Status" name="status" :options="$statusOptions" :value="$editUser->status ?? 'active'" :required="true" />

    <div class="sm:col-span-2">
        <x-form.select
            label="Assigned Branch"
            name="branch_id"
            :options="$branches->pluck('name', 'id')->all()"
            :value="$editUser->branch_id ?? ''"
            placeholder="All Branches (unscoped)"
        />
        <p class="mt-1.5 text-xs text-gray-500">Only applies to Receptionist and Healthcare Staff — Super Admin and Clinic Admin always see every branch regardless of this setting.</p>
    </div>
</div>

<div class="mt-8 flex items-center gap-3">
    <x-btn type="submit" variant="primary">
        {{ isset($editUser) ? 'Save Changes' : 'Create User' }}
    </x-btn>
    <x-btn :href="route('admin.users.index')" variant="ghost">
        Cancel
    </x-btn>
</div>
