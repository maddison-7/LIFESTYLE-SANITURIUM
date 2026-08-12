@php
    $genderOptions = ['male' => __('Male'), 'female' => __('Female'), 'other' => __('Other')];
    $serviceOptions = $services->pluck('name', 'id')->all();
    $branchOptions = $branches->pluck('name', 'id')->all();
@endphp

<x-layouts.app
    title="Book an Appointment"
    description="Request an appointment with Lifestyle Sanitarium Clinic. Our team will contact you to confirm."
>
    <section class="py-16 sm:py-20">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-sm font-semibold uppercase tracking-wide text-primary-600">{{ __('Book an Appointment') }}</p>
                <h1 class="mt-2 text-3xl sm:text-4xl font-bold text-gray-900">{{ __('Request an Appointment') }}</h1>
                <p class="mt-4 text-gray-600 text-pretty">
                    {{ __('Fill in the form below and our team will contact you to confirm your appointment. Submitting a request does not automatically confirm it.') }}
                </p>
            </div>

            <div class="mt-10 rounded-2xl border border-surface-200 bg-white p-6 sm:p-8">
                <form method="POST" action="{{ route('appointments.store') }}" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <x-form.input :label="__('Full Name')" name="full_name" :required="true" />
                        <x-form.input :label="__('Phone Number')" name="phone" type="tel" :required="true" :hint="__(\"We'll use this number to contact you.\")" />
                        <x-form.input :label="__('Email Address')" name="email" type="email" :hint="__('Optional')" />
                        <x-form.select :label="__('Gender')" name="gender" :options="$genderOptions" :placeholder="__('Select gender')" :required="true" />

                        <x-form.select
                            :label="__('Service')"
                            name="service_id"
                            :options="$serviceOptions"
                            :placeholder="__('Select a service')"
                            :value="$preselectedService?->id"
                            :required="true"
                        />
                        <x-form.select :label="__('Branch')" name="branch_id" :options="$branchOptions" :placeholder="__('Select a branch')" :required="true" />

                        <x-form.input :label="__('Preferred Date')" name="appointment_date" type="date" :min="now()->toDateString()" :required="true" />
                        <x-form.input :label="__('Preferred Time')" name="appointment_time" type="time" :required="true" />
                    </div>

                    <x-form.textarea :label="__('Additional Message')" name="message" :hint="__('Optional — share anything that will help our team prepare.')" />

                    <x-btn type="submit" variant="primary" size="lg" class="w-full">
                        {{ __('Request Appointment') }}
                    </x-btn>
                </form>
            </div>

            <div class="mt-10 text-center">
                <p class="text-sm text-gray-500">{{ __('Prefer to speak with someone directly?') }}</p>
                <div class="mt-4 flex flex-wrap justify-center gap-4">
                    <x-btn :href="whatsapp_link()" variant="whatsapp" target="_blank" rel="noopener noreferrer">
                        {{ __('Chat on WhatsApp') }}
                    </x-btn>
                    <x-btn :href="route('contact')" variant="ghost">
                        {{ __('View Phone Numbers') }}
                    </x-btn>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
