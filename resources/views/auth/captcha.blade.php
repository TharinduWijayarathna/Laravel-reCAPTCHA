<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Please complete the CAPTCHA to verify you are not a robot.') }}
    </div>

    <form method="POST" action="{{ route('captcha.verify') }}">
        @csrf

        <!-- CAPTCHA -->
        <div class="mt-3">
            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
        </div>

        <x-input-error :messages="$errors->get('g-recaptcha-response')" class="mt-2" />

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Verify CAPTCHA') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
