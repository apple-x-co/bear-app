{{ extends ('layout/AdminPage/page') }}

{{ setBlock ('title') }}Fieldset Demo | {{ parentBlock () }}{{ endBlock () }}

{{ $this->navVisible = false }}
{{ setBlock ('body_content') }}
<form method="post">
    <div class="mt-5">
        <div class="mt-5">
            <h2 class="inline-block text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">Home</h2>
            {{= AdminFormError(form: $this->form, input: 'home') }}
            {{ $home = $this->form->home }}
            <label class="block mt-5">
                <span class="block text-sm font-sans font-normal text-slate-700">Zip</span>
                {{= AdminInput(form: $this->form, input: 'zip', fieldset: $home) }}
                {{= AdminFieldsetError(fieldset: $home, input: 'zip') }}
            </label>
            <label class="block mt-5">
                <span class="block text-sm font-sans font-normal text-slate-700">State</span>
                {{= AdminInput(form: $this->form, input: 'state', fieldset: $home) }}
                {{= AdminFieldsetError(fieldset: $home, input: 'state') }}
            </label>
            <label class="block mt-5">
                <span class="block text-sm font-sans font-normal text-slate-700">City</span>
                {{= AdminInput(form: $this->form, input: 'city', fieldset: $home) }}
                {{= AdminFieldsetError(fieldset: $home, input: 'city') }}
            </label>
            <label class="block mt-5">
                <span class="block text-sm font-sans font-normal text-slate-700">Street</span>
                {{= AdminInput(form: $this->form, input: 'street', fieldset: $home) }}
                {{= AdminFieldsetError(fieldset: $home, input: 'street') }}
            </label>
            <label class="block mt-5">
                <span class="block text-sm font-sans font-normal text-slate-700">House type</span>
                {{= AdminRadio(form: $this->form, input: 'houseType', fieldset: $home) }}
                {{= AdminFieldsetError(fieldset: $home, input: 'houseType') }}
            </label>
            <label class="block mt-5">
                <span class="block text-sm font-sans font-normal text-slate-700">Smartphone you own</span>
                {{= AdminRadio(form: $this->form, input: 'smartphones', fieldset: $home) }}
                {{= AdminFieldsetError(fieldset: $home, input: 'smartphones') }}
            </label>
        </div>

        <div class="mt-5">
            <h2 class="inline-block text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">Deliveries</h2>
            {{= AdminFormError(form: $this->form, input: 'deliveries') }}
            {{ foreach ($this->form->deliveries as $index => $delivery):  }}
            <div class="mt-5">
                <h2 class="inline-block text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">delivery{{= $index + 1 }}</h2>
                <label class="block mt-5">
                    <span class="block text-sm font-sans font-normal text-slate-700">Zip</span>
                    {{= AdminInput(form: $this->form, input: 'zip', fieldset: $delivery) }}
                    {{= AdminFieldsetError(fieldset: $delivery, input: 'zip') }}
                </label>
                <label class="block mt-5">
                    <span class="block text-sm font-sans font-normal text-slate-700">State</span>
                    {{= AdminInput(form: $this->form, input: 'state', fieldset: $delivery) }}
                    {{= AdminFieldsetError(fieldset: $delivery, input: 'state') }}
                </label>
                <label class="block mt-5">
                    <span class="block text-sm font-sans font-normal text-slate-700">City</span>
                    {{= AdminInput(form: $this->form, input: 'city', fieldset: $delivery) }}
                    {{= AdminFieldsetError(fieldset: $delivery, input: 'city') }}
                </label>
                <label class="block mt-5">
                    <span class="block text-sm font-sans font-normal text-slate-700">Street</span>
                    {{= AdminInput(form: $this->form, input: 'street', fieldset: $delivery) }}
                    {{= AdminFieldsetError(fieldset: $delivery, input: 'street') }}
                </label>
                <label class="block mt-5">
                    <span class="block text-sm font-sans font-normal text-slate-700">House type</span>
                    {{= AdminRadio(form: $this->form, input: 'houseType', fieldset: $delivery) }}
                    {{= AdminFieldsetError(fieldset: $delivery, input: 'houseType') }}
                </label>
                <label class="block mt-5">
                    <span class="block text-sm font-sans font-normal text-slate-700">Smartphone you own</span>
                    {{= AdminRadio(form: $this->form, input: 'smartphones', fieldset: $delivery) }}
                    {{= AdminFieldsetError(fieldset: $delivery, input: 'smartphones') }}
                </label>
            </div>
            {{ endforeach }}
        </div>

        <label class="block mt-20">
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
