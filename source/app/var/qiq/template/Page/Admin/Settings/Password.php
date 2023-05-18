{{ extends ('layout/Admin/settings') }}

{{ setBlock ('body') }}
<div class="h-[100svh] grid place-content-center">
    <div class="relative w-80 md:w-96 h-min p-5 md:p-8 rounded-xl bg-white shadow-[0_1px_3px_rgba(15,23,42,0.03),0_1px_2px_rgba(15,23,42,0.06)] ring-1 ring-slate-600/[0.04]">
        <h2 class="text-xl text-center tracking-widest font-sans font-bold">Password</h2>

        <form method="post">
            {{ if (isset($this->authError) && $this->authError): }}
            {{= render ('partials/Admin/AlertError', ['text' => 'Authentication error']) }}
            {{ endif }}
            <div class="mt-5">
                <label class="block mt-5 relative">
                    <span class="block top-2 left-3 text-sm font-thin text-slate-500 tracking-wide select-none">Old password</span>
                    {{= AdminText(form: $this->form, input: 'oldPassword') }}
                    {{= AdminFormError(form: $this->form, input: 'oldPassword') }}
                </label>
                <label class="block mt-5 relative">
                    <span class="block top-2 left-3 text-sm font-thin text-slate-500 tracking-wide select-none">New password</span>
                    {{= AdminText(form: $this->form, input: 'password') }}
                    {{= AdminFormError(form: $this->form, input: 'password') }}
                </label>
                <label class="block mt-5 relative">
                    <span class="block top-2 left-3 text-sm font-thin text-slate-500 tracking-wide select-none">Confirm new password</span>
                    {{= AdminText(form: $this->form, input: 'passwordConfirmation') }}
                    {{= AdminFormError(form: $this->form, input: 'passwordConfirmation') }}
                </label>
                <label class="block mt-5 text-center">
                    {{= AdminSubmit(form: $this->form, input: 'update', attribs: ['value' => 'Update password', 'data-submit-once' => '1']) }}
                    {{= CsrfTokenField(form: $this->form) }}
                </label>
            </div>
        </form>
        <div class="absolute top-full right-0 w-full h-px rounded-full max-w-sm bg-gradient-to-r from-transparent from-10% via-purple-500 to-transparent drop-shadow-xl"></div>
    </div>
</div>
{{ endBlock () }}
