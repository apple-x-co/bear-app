{{ extends ('layout/Admin/base') }}

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
                {{= render ('partials/Admin/AlertError', ['text' => 'Authentication error']) }}
            {{ endif }}
            {{ if (isset($this->recaptchaError) && $this->recaptchaError): }}
                {{= render ('partials/Admin/AlertError', ['text' => 'CAPTCHA error']) }}
            {{ endif }}
            <div class="mt-5">
                <label class="block">
                    <span class="block text-sm font-sans font-normal text-slate-700">Username</span>
                    {{= AdminText(form: $this->form, input: 'username') }}
                    {{= AdminFormError(form: $this->form, input: 'username') }}
                </label>
                <label class="block mt-5">
                    <span class="block text-sm font-sans font-normal text-slate-700">Password</span>
                    {{= AdminText(form: $this->form, input: 'password') }}
                    {{= AdminFormError(form: $this->form, input: 'password') }}
                </label>
                <label class="block mt-5 relative">
                    <span class="block top-2 left-3 text-sm font-thin text-slate-500 tracking-wide select-none">Remember</span>
                    {{= AdminCheckbox(form: $this->form, input: 'remember') }}
                    {{= AdminFormError(form: $this->form, input: 'remember') }}
                </label>
                <div class="ml-[-11px] lg:ml-0 mt-5 g-recaptcha" data-sitekey="{{= $this->googleRecaptchaSiteKey }}" data-size="normal" data-tabindex="4" data-callback="gRecaptchaChecked" data-expired-callback="gRecaptchaExpired" data-error-callback="gRecaptchaError"></div>
                <label class="block mt-5 text-center">
                    {{= AdminSubmit(form: $this->form, input: 'login', attribs: ['id' => 'login', 'value' => 'LOGIN', 'disabled' => 'disabled', 'data-submit-once' => '1']) }}
                    {{= CsrfTokenField(form: $this->form) }}
                </label>
            </div>
        </form>
        <div class="absolute top-full right-0 w-full h-px rounded-full max-w-sm bg-gradient-to-r from-transparent from-10% via-purple-500 to-transparent drop-shadow-xl"></div>
    </div>
</div>
{{ endBlock () }}
