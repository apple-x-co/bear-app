{{ extends ('layout/AdminPage/page') }}

{{ setBlock ('title') }}Fieldset Demo | {{ parentBlock () }}{{ endBlock () }}

{{ $this->navVisible = false }}
{{ setBlock ('body_content') }}
<form method="post">
    <div class="mt-5">
        {{ foreach ($this->form->addresses as $address):  }}
        <label class="block mt-5">
            <span class="block text-sm font-sans font-normal text-slate-700">Zip</span>
            {{= AdminInput(form: $this->form, input: 'zip', fieldset: $address) }}
        </label>
        <label class="block mt-5">
            <span class="block text-sm font-sans font-normal text-slate-700">State</span>
            {{= AdminInput(form: $this->form, input: 'state', fieldset: $address) }}
        </label>
        <label class="block mt-5">
            <span class="block text-sm font-sans font-normal text-slate-700">City</span>
            {{= AdminInput(form: $this->form, input: 'city', fieldset: $address) }}
        </label>
        <label class="block mt-5">
            <span class="block text-sm font-sans font-normal text-slate-700">Street</span>
            {{= AdminInput(form: $this->form, input: 'street', fieldset: $address) }}
        </label>
        {{ endforeach }}
        {{= AdminFormError(form: $this->form, input: 'addresses') }}
        <label class="block mt-5 text-center">
            {{= AdminSubmit(form: $this->form, input: 'submit', attribs: ['value' => 'UPLOAD', 'data-submit-once' => '1']) }}
            {{= CsrfTokenField(form: $this->form) }}
        </label>
    </div>
</form>
{{ endBlock () }}
