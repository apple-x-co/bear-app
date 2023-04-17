{{ extends ('layout/AdminPage/page') }}

{{ setBlock ('title') }}Contact Demo | {{ parentBlock () }}{{ endBlock () }}

{{ $this->navVisible = false }}
{{ setBlock ('body_content') }}
<form method="post">
    {{ if ($this->form->mode == 'CONFIRM'): }}
    <div class="mt-5">
        <label class="block">
            <span class="block text-sm font-thin text-slate-500 tracking-wide">Username</span>
            {{= $this->form->username }}
            {{= AdminHidden(form: $this->form, input: 'username') }}
        </label>
        <label class="block mt-5 text-center">
            {{= AdminSubmit(form: $this->form, input: 'mode', attribs: ['value' => 'INPUT', 'data-submit-once' => '1']) }}
            {{= AdminSubmit(form: $this->form, input: 'mode', attribs: ['value' => 'COMPLETE', 'data-submit-once' => '1']) }}
            {{= CsrfTokenField(form: $this->form) }}
        </label>
    </div>
    {{ else: }}
    <div class="mt-5">
        <label class="block">
            <span class="block text-sm font-thin text-slate-500 tracking-wide">Username</span>
            {{= AdminText(form: $this->form, input: 'username') }}
            {{= AdminFormError(form: $this->form, input: 'username') }}
        </label>
        <label class="block mt-5 text-center">
            {{= AdminSubmit(form: $this->form, input: 'mode', attribs: ['value' => 'CONFIRM', 'data-submit-once' => '1']) }}
            {{= CsrfTokenField(form: $this->form) }}
        </label>
    </div>
    {{ endif }}
</form>
{{ endBlock () }}
