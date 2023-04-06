{{ extends ('layout/AdminPage/base') }}

{{ setBlock ('head_scripts') }}
    {{ parentBlock () }}
    <script>
        function gRecaptchaChecked() {
            document.getElementById('login').removeAttribute('disabled');
        }
        function gRecaptchaExpired() {
            document.getElementById('login').setAttribute('disabled', 'disabled');
        }
        function gRecaptchaError() {
            document.getElementById('login').setAttribute('disabled', 'disabled');
        }
    </script>
{{ endBlock () }}

{{ $this->useGRecaptcha = true }}
{{ setBlock ('body') }}
<div class="h-[100svh] grid place-content-center">
    <div class="relative w-80 md:w-96 h-min p-5 md:p-8 rounded-xl bg-white shadow-[0_1px_3px_rgba(15,23,42,0.03),0_1px_2px_rgba(15,23,42,0.06)] ring-1 ring-slate-600/[0.04]">
        <h2 class="text-xl text-center tracking-widest font-sans font-bold">BearApp</h2>

        <form method="post">
            {{ if (isset($this->authError) && $this->authError): }}
                {{= render ('partials/AdminPage/AlertError', ['text' => 'Authentication error']) }}
            {{ endif }}
            {{ if (isset($this->recaptchaError) && $this->recaptchaError): }}
                {{= render ('partials/AdminPage/AlertError', ['text' => 'CAPTCHA error']) }}
            {{ endif }}
            <div class="mt-5">
                <label class="block">
                    <span class="block text-sm font-sans font-normal text-slate-700">Username</span>
                    {{= AdminInput($this->form, 'username') }}
                    {{= AdminFormError($this->form, 'username') }}
                </label>
                <label class="block mt-5">
                    <span class="block text-sm font-sans font-normal text-slate-700">Password</span>
                    {{= AdminInput($this->form, 'password') }}
                    {{= AdminFormError($this->form, 'password') }}
                </label>
                <div class="ml-[-11px] lg:ml-0 mt-5 g-recaptcha" data-sitekey="{{= $this->gRecaptchaSiteKey }}" data-size="normal" data-tabindex="3" data-callback="gRecaptchaChecked" data-expired-callback="gRecaptchaExpired" data-error-callback="gRecaptchaError"></div>
                <label class="block mt-5 text-center">
                    {{= AdminSubmit($this->form, 'login', ['id' => 'login', 'value' => 'LOGIN', 'disabled' => 'disabled']) }}
                    {{= CsrfTokenField($this->form) }}
                </label>
            </div>
        </form>
        <div class="absolute top-full right-0 w-full h-px rounded-full max-w-sm bg-gradient-to-r from-transparent from-10% via-purple-500 to-transparent drop-shadow-xl"></div>
    </div>
</div>
{{ endBlock () }}
