{{ extends ('layout/AdminPage/page') }}

{{ setBlock ('title') }}Multiple Demo | {{ parentBlock () }}{{ endBlock () }}

{{ $this->navVisible = false }}
{{ setBlock ('body_content') }}
<form method="post">
    <div class="mt-5">
        <label class="block">
            <span class="block text-sm font-sans font-normal text-slate-700">Fruits</span>
            {{= AdminCheckbox(form: $this->form, input: 'fruits') }}
            {{= AdminFormError(form: $this->form, input: 'fruits') }}
        </label>
        <label class="block mt-5">
            <span class="block text-sm font-sans font-normal text-slate-700">Primary languages</span>
            {{= AdminSelect(form: $this->form, input: 'primaryLanguage') }}
            {{= AdminFormError(form: $this->form, input: 'primaryLanguage') }}
        </label>
        <label class="block mt-5">
            <span class="block text-sm font-sans font-normal text-slate-700">Available Languages</span>
            {{= AdminSelect(form: $this->form, input: 'languages') }}
            {{= AdminFormError(form: $this->form, input: 'languages') }}
        </label>
        <label class="block mt-5">
            <span class="block text-sm font-sans font-normal text-slate-700">Programming Languages</span>
            {{= AdminSelect(form: $this->form, input: 'programmingLanguages') }}
            {{= AdminFormError(form: $this->form, input: 'programmingLanguages') }}
        </label>
        <label class="block mt-5">
            <span class="block text-sm font-sans font-normal text-slate-700">Agree</span>
            {{= AdminSelect(form: $this->form, input: 'agree') }}
            {{= AdminFormError(form: $this->form, input: 'agree') }}
        </label>
        <label class="block mt-5 text-center">
            {{= AdminSubmit(form: $this->form, input: 'submit', attribs: ['value' => 'UPLOAD', 'data-submit-once' => '1']) }}
            {{= CsrfTokenField(form: $this->form) }}
        </label>
    </div>
</form>
{{ endBlock () }}
