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

{{ $this->addData(['useGoogleRecaptcha' => true]) }}
{{ setBlock ('body') }}
<div class="h-[100svh] grid place-content-center">
    <div class="relative w-80 md:w-96 h-min p-5 md:p-8 rounded-xl bg-white shadow-[0_1px_3px_rgba(15,23,42,0.03),0_1px_2px_rgba(15,23,42,0.06)] ring-1 ring-slate-600/[0.04]">
        <h2 class="text-xl text-center tracking-widest font-sans font-bold">BearApp</h2>

        <form method="post">
            {{ if (isset($authError) && $authError): }}
                {{= render('partials/Admin/AlertError', ['text' => 'Authentication error']) }}
            {{ endif }}
            {{ if (isset($recaptchaError) && $recaptchaError): }}
                {{= render('partials/Admin/AlertError', ['text' => 'CAPTCHA error']) }}
            {{ endif }}
            <div class="mt-5">
                <label class="block">
                    <span class="block text-sm font-sans font-normal text-slate-700 select-none">Username</span>
                    {{= adminText(form: $form, input: 'username') }}
                    {{= adminFormError(form: $form, input: 'username') }}
                </label>
                <label class="block mt-5">
                    <span class="block text-sm font-sans font-normal text-slate-700 select-none">Password</span>
                    {{= adminText(form: $form, input: 'password') }}
                    {{= adminFormError(form: $form, input: 'password') }}
                </label>
                <p class="text-sm text-right mt-1"><a href="{{= url('/admin/forgot-password') }}">Forgot password?</a></p>
                <label class="block mt-5">
                    <span class="block top-2 left-3 text-sm font-normal text-slate-700 tracking-wide select-none">Remember</span>
                    {{= adminCheckBox(form: $form, input: 'remember') }}
                    {{= adminFormError(form: $form, input: 'remember') }}
                </label>
                <div class="ml-[-11px] lg:ml-0 mt-5 g-recaptcha" data-sitekey="{{a googleRecaptchaSiteKey() }}" data-size="normal" data-tabindex="4" data-callback="gRecaptchaChecked" data-expired-callback="gRecaptchaExpired" data-error-callback="gRecaptchaError"></div>
                <label class="block mt-5 text-center">
                    {{= adminSubmit(form: $form, input: 'login', attribs: ['id' => 'login', 'value' => 'LOGIN', 'disabled' => 'disabled', 'data-submit-once' => '1']) }}
                    {{= csrfTokenField(form: $form) }}
                </label>
            </div>
        </form>
        <div class="absolute top-full right-0 w-full h-px rounded-full max-w-sm bg-gradient-to-r from-transparent from-10% via-purple-500 to-transparent drop-shadow-xl"></div>
    </div>
    <p class="text-sm text-center mt-2"><a href="{{= url('/admin/join') }}">Create an account</a></p>
</div>
{{ endBlock () }}
