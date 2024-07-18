<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by entering the code we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('A new verification code has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.verify') }}" id="verify-form">
        @csrf

        <div>
            <x-input-label for="code" :value="__('Verification Code')" />
            <x-text-input id="code" class="block mt-1 w-full" type="text" name="code" required autofocus />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <!-- Captcha -->
        <div>
            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
        </div>

        <div class="flex items-center justify-end mt-4">
            <label for="resend"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Resend Verification Code') }}
            </label>
            <x-primary-button class="ml-1" type="button" onclick="userVerify(event)">
                {{ __('Verify') }}
            </x-primary-button>
        </div>
    </form>

    <form method="POST" action="{{ route('verification.resend') }}" class="mt-4" style="display: none;">
        @csrf
        <div class="flex items-center justify-end">
            <x-primary-button id="resend">
                {{ __('Resend Verification Code') }}
            </x-primary-button>
        </div>
    </form>

    @push('scripts')
    <script>
        function userVerify(e) {
          e.preventDefault();
          grecaptcha.ready(function() {
            grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'verify'}).then(function(token) {
                document.getElementById('g-recaptcha-response').value = token;
                document.getElementById('verify-form').submit();
            });
          });
        }
    </script>
    @endpush
</x-guest-layout>
