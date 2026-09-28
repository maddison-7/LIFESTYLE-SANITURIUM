@php
    $socials = [
        'facebook_url' => 'Facebook',
        'instagram_url' => 'Instagram',
        'tiktok_url' => 'TikTok',
        'youtube_url' => 'YouTube',
    ];
@endphp

<footer class="bg-primary-950 text-primary-100">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-14 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
        <div>
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-600 text-white font-bold text-lg">L</span>
                <span class="leading-tight">
                    <span class="block text-base font-bold text-white">MADILA LIFESTYLE</span>
                    <span class="block text-[11px] font-medium text-primary-300 tracking-wide">CLINIC</span>
                </span>
            </a>
            <p class="mt-4 text-sm text-primary-200">{{ $siteSettings['tagline'] ?? 'Afya Bora, Maisha Bora.' }}</p>
            <p class="mt-1 text-xs text-primary-300">{{ $siteSettings['secondary_tagline'] ?? 'Professional • Confidential • Patient-Centred Care' }}</p>

            @if (!empty(array_filter($socials, fn ($label, $key) => !empty($siteSettings[$key] ?? null), ARRAY_FILTER_USE_BOTH)))
                <div class="mt-5 flex gap-3">
                    @foreach ($socials as $key => $label)
                        @if (!empty($siteSettings[$key] ?? null))
                            <a href="{{ $siteSettings[$key] }}" target="_blank" rel="noopener noreferrer" class="text-primary-200 hover:text-white text-sm underline underline-offset-2">
                                {{ $label }}
                            </a>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <h3 class="text-sm font-semibold text-white uppercase tracking-wide">{{ __('Quick Links') }}</h3>
            <ul class="mt-4 space-y-2.5 text-sm">
                <li><a href="{{ route('home') }}" class="text-primary-200 hover:text-white transition-base">{{ __('Home') }}</a></li>
                <li><a href="{{ route('about') }}" class="text-primary-200 hover:text-white transition-base">{{ __('About Us') }}</a></li>
                <li><a href="{{ route('services.index') }}" class="text-primary-200 hover:text-white transition-base">{{ __('Services') }}</a></li>
                <li><a href="{{ route('team.index') }}" class="text-primary-200 hover:text-white transition-base">{{ __('Healthcare Team') }}</a></li>
                <li><a href="{{ route('education.index') }}" class="text-primary-200 hover:text-white transition-base">{{ __('Health Education') }}</a></li>
                <li><a href="{{ route('branches.index') }}" class="text-primary-200 hover:text-white transition-base">{{ __('Branches') }}</a></li>
                <li><a href="{{ route('contact') }}" class="text-primary-200 hover:text-white transition-base">{{ __('Contact') }}</a></li>
            </ul>
        </div>

        <div>
            <h3 class="text-sm font-semibold text-white uppercase tracking-wide">{{ __('Our Branches') }}</h3>
            <ul class="mt-4 space-y-3 text-sm">
                @forelse ($navBranches ?? [] as $branch)
                    <li>
                        <p class="text-primary-100">{{ $branch->name }}</p>
                        <span class="inline-block mt-1 text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $branch->isOpen() ? 'bg-primary-500/20 text-primary-200' : 'bg-white/10 text-primary-300' }}">
                            {{ __($branch->statusLabel()) }}
                        </span>
                    </li>
                @empty
                    <li class="text-primary-300">{{ __('Branch information coming soon.') }}</li>
                @endforelse
            </ul>
        </div>

        <div>
            <h3 class="text-sm font-semibold text-white uppercase tracking-wide">{{ __('Contact') }}</h3>
            <ul class="mt-4 space-y-2.5 text-sm text-primary-200">
                @if (!empty($siteSettings['phone_primary'] ?? null))
                    <li><a href="tel:{{ preg_replace('/\s+/', '', $siteSettings['phone_primary']) }}" class="hover:text-white transition-base">{{ $siteSettings['phone_primary'] }}</a></li>
                @endif
                @if (!empty($siteSettings['phone_secondary'] ?? null))
                    <li><a href="tel:{{ preg_replace('/\s+/', '', $siteSettings['phone_secondary']) }}" class="hover:text-white transition-base">{{ $siteSettings['phone_secondary'] }}</a></li>
                @endif
                @if (!empty($siteSettings['phone_tertiary'] ?? null))
                    <li><a href="tel:{{ preg_replace('/\s+/', '', $siteSettings['phone_tertiary']) }}" class="hover:text-white transition-base">{{ $siteSettings['phone_tertiary'] }}</a></li>
                @endif
                @if (!empty($siteSettings['email'] ?? null))
                    <li><a href="mailto:{{ $siteSettings['email'] }}" class="hover:text-white transition-base">{{ $siteSettings['email'] }}</a></li>
                @endif
                @if (!empty($siteSettings['address'] ?? null))
                    <li class="text-primary-300">{{ $siteSettings['address'] }}</li>
                @endif
                <li>
                    <a href="{{ whatsapp_link() }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 mt-1 font-medium text-primary-100 hover:text-white transition-base">
                        {{ __('Chat on WhatsApp') }}
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-5 text-xs text-primary-300 leading-relaxed">
            {{ $siteSettings['medical_disclaimer'] ?? 'Information provided on this website is intended for general health education and information about our services. It is not a substitute for professional medical consultation, diagnosis or treatment. Please contact Lifestyle Sanitarium Clinic for professional medical advice.' }}
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-5 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-primary-300">
            <p>&copy; {{ now()->year }} Lifestyle Sanitarium Clinic. {{ __('All rights reserved.') }}</p>
            <a href="{{ route('privacy-policy') }}" class="hover:text-white transition-base">{{ __('Privacy Policy') }}</a>
        </div>
    </div>
</footer>
