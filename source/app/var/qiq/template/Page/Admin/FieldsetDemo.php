{{ extends ('layout/AdminPage/page') }}

{{ setBlock ('title') }}Fieldset Demo | {{ parentBlock () }}{{ endBlock () }}

{{ $this->navVisible = false }}
{{ setBlock ('body_content') }}
<form method="post">
    <div class="mt-5">
        {{ foreach ($this->form->addresses as $index => $address):  }}
        <div class="mt-5">
            <h2 class="inline-block text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">Address {{= $index + 1 }}</h2>
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
            <label class="block mt-5">
                <span class="block text-sm font-sans font-normal text-slate-700">House type</span>
                {{= AdminRadio(form: $this->form, input: 'houseType', fieldset: $address) }}
            </label>
            <label class="block mt-5">
                <span class="block text-sm font-sans font-normal text-slate-700">Smartphone you own</span>
                {{= AdminRadio(form: $this->form, input: 'smartphones', fieldset: $address) }}
            </label>
        </div>
        {{ endforeach }}
        {{= AdminFormError(form: $this->form, input: 'addresses') }}

        <label class="block mt-5">
            <span class="block text-sm font-sans font-normal text-slate-700">Note</span>
            {{= AdminInput(form: $this->form, input: 'note') }}
            {{= AdminFormError(form: $this->form, input: 'note') }}
        </label>
        <label class="block mt-5">
            <span class="block text-sm font-sans font-normal text-slate-700">Agree</span>
            {{= AdminSelect(form: $this->form, input: 'agree') }}
            {{= AdminFormError(form: $this->form, input: 'agree') }}
        </label>
        <label class="block mt-5 text-center">
            {{= AdminSubmit(form: $this->form, input: 'submit', attribs: ['value' => 'SEND', 'data-submit-once' => '1']) }}
            {{= CsrfTokenField(form: $this->form) }}
        </label>
    </div>
</form>
{{ endBlock () }}
