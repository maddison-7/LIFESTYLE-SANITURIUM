<x-layouts.app
    title="Privacy Policy"
    description="Lifestyle Sanitarium Clinic's privacy policy on how we collect, use and protect your information."
>
    <section class="bg-primary-950 py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm font-semibold uppercase tracking-wide text-primary-300">Legal</p>
            <h1 class="mt-3 text-4xl sm:text-5xl font-extrabold text-white">Privacy Policy</h1>
        </div>
    </section>

    <section class="py-20">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 space-y-10">

            <div class="rounded-2xl bg-primary-50 border border-primary-100 p-6">
                <p class="text-sm text-primary-800 leading-relaxed">
                    {{ setting('medical_disclaimer') }}
                </p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-gray-900">Information We Collect</h2>
                <p class="mt-3 text-gray-600 leading-relaxed">
                    When you submit an appointment request or contact us through this website, we may collect information you provide directly, such as your name, phone number, email address, gender, and details relevant to the appointment you are requesting.
                </p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-gray-900">Why We Collect It</h2>
                <p class="mt-3 text-gray-600 leading-relaxed">
                    We use the information you provide to respond to appointment requests, confirm and manage appointments, communicate with you about your visit, and improve the services we offer. We do not use appointment or contact information for purposes unrelated to your care and communication with our clinic.
                </p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-gray-900">Appointment Information</h2>
                <p class="mt-3 text-gray-600 leading-relaxed">
                    Appointment details you submit are reviewed by authorised clinic staff to confirm, reschedule, or follow up on your request. This information is not publicly visible and is only accessible to authorised personnel.
                </p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-gray-900">Contact Information</h2>
                <p class="mt-3 text-gray-600 leading-relaxed">
                    Contact details such as phone number and email are used solely to communicate with you regarding your enquiry or appointment.
                </p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-gray-900">Data Protection</h2>
                <p class="mt-3 text-gray-600 leading-relaxed">
                    We take reasonable technical and organisational measures to protect the information you share with us from unauthorised access, alteration, or disclosure.
                </p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-gray-900">Your Rights</h2>
                <p class="mt-3 text-gray-600 leading-relaxed">
                    You may contact us at any time to ask what information we hold about you, to request corrections, or to request that your information be removed, subject to any recordkeeping requirements applicable to healthcare providers.
                </p>
            </div>

            <div>
                <h2 class="text-xl font-bold text-gray-900">Contact Us</h2>
                <p class="mt-3 text-gray-600 leading-relaxed">
                    If you have questions about this privacy policy or how your information is handled, please reach out to us through the details on our <a href="{{ route('contact') }}" class="text-primary-700 font-semibold hover:text-primary-800">Contact page</a>.
                </p>
            </div>
        </div>
    </section>
</x-layouts.app>
