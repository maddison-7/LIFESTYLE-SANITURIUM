<x-layouts.app
    title="About Us"
    description="Learn about Lifestyle Sanitarium Clinic's mission, vision and commitment to professional, confidential, patient-centred healthcare."
>
    <section class="bg-primary-950 py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm font-semibold uppercase tracking-wide text-primary-300">{{ __('About Us') }}</p>
            <h1 class="mt-3 text-4xl sm:text-5xl font-extrabold text-white">Lifestyle Sanitarium Clinic</h1>
            <p class="mt-4 text-primary-100 max-w-2xl mx-auto text-pretty">{{ setting('secondary_tagline', 'Professional • Confidential • Patient-Centred Care') }}</p>
        </div>
    </section>

    <section class="py-20 sm:py-24">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 space-y-16">

            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ __('Who We Are') }}</h2>
                <p class="mt-4 text-gray-600 leading-relaxed text-pretty">
                    {{ __("Lifestyle Sanitarium Clinic is a healthcare provider offering professional and confidential services centred on men's and women's reproductive health, urinary system health, counselling and consultation, medical testing, treatment and follow-up care. We are committed to responsible, respectful and patient-centred healthcare delivery.") }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-10">
                <div class="rounded-2xl border border-surface-200 p-8">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary-50 text-primary-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0-14v4l3 2"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-xl font-bold text-gray-900">{{ __('Our Mission') }}</h3>
                    <p class="mt-3 text-sm text-gray-600 leading-relaxed">
                        {{ __('To provide professional, confidential and patient-centred healthcare services, guiding patients through consultation, testing, treatment and follow-up with care and respect.') }}
                    </p>
                </div>

                <div class="rounded-2xl border border-surface-200 p-8">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary-50 text-primary-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-xl font-bold text-gray-900">{{ __('Our Vision') }}</h3>
                    <p class="mt-3 text-sm text-gray-600 leading-relaxed">
                        {{ __('To be a trusted healthcare partner for men and women seeking professional and confidential care, expanding our reach responsibly to serve more communities.') }}
                    </p>
                </div>
            </div>

            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ __('Our Values') }}</h2>
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-6">
                    @foreach ([
                        'Professionalism' => 'We hold ourselves to high standards of professional healthcare practice.',
                        'Confidentiality' => 'Patient privacy is protected at every stage of care.',
                        'Respect' => 'Every patient is treated with dignity, regardless of the concern they bring to us.',
                    ] as $value => $desc)
                        <div class="rounded-xl bg-surface-50 p-6">
                            <h3 class="font-bold text-gray-900">{{ __($value) }}</h3>
                            <p class="mt-2 text-sm text-gray-600">{{ __($desc) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-10">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ __('Patient-Centred Care') }}</h2>
                    <p class="mt-3 text-sm text-gray-600 leading-relaxed">
                        {{ __('We design our services around the needs of each patient — from initial consultation through testing, treatment and follow-up — so that care feels personal, not procedural.') }}
                    </p>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ __('Privacy & Confidentiality') }}</h2>
                    <p class="mt-3 text-sm text-gray-600 leading-relaxed">
                        {{ __('We understand the sensitive nature of reproductive and urinary health concerns. Your information and visit details are handled with strict confidentiality.') }}
                    </p>
                </div>
            </div>

            <div class="rounded-2xl bg-primary-50 border border-primary-100 p-8 text-center">
                <p class="text-sm text-primary-800 leading-relaxed">
                    {{ setting('medical_disclaimer') }}
                </p>
            </div>
        </div>
    </section>
</x-layouts.app>
