<?php
/** @var Qiq\Engine&Qiq\Helper\Html\HtmlHelpers $this */
$this->setLayout('layout/base');
?>
<h2>UserPage:FieldsetDemo</h2><br>
<form method="post">
    <div class="mt-5">
        <div class="mt-5">
            <h2 class="inline-block text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">Home</h2>
            {{= userFormError(form: $form, input: 'home') }}
            {{ $home = $form->home }}
            <label class="block mt-5">
                <span class="block text-sm font-sans font-normal text-slate-700">Zip</span>
                {{= userText(form: $form, input: 'zip', fieldset: $home) }}
                {{= userFieldsetError(fieldset: $home, input: 'zip') }}
            </label>
            <label class="block mt-5">
                <span class="block text-sm font-sans font-normal text-slate-700">State</span>
                {{= userText(form: $form, input: 'state', fieldset: $home) }}
                {{= userFieldsetError(fieldset: $home, input: 'state') }}
            </label>
            <label class="block mt-5">
                <span class="block text-sm font-sans font-normal text-slate-700">City</span>
                {{= userText(form: $form, input: 'city', fieldset: $home) }}
                {{= userFieldsetError(fieldset: $home, input: 'city') }}
            </label>
            <label class="block mt-5">
                <span class="block text-sm font-sans font-normal text-slate-700">Street</span>
                {{= userText(form: $form, input: 'street', fieldset: $home) }}
                {{= userFieldsetError(fieldset: $home, input: 'street') }}
            </label>
            <label class="block mt-5">
                <span class="block text-sm font-sans font-normal text-slate-700">House type</span>
                {{= userRadio(form: $form, input: 'houseType', fieldset: $home) }}
                {{= userFieldsetError(fieldset: $home, input: 'houseType') }}
            </label>
            <label class="block mt-5">
                <span class="block text-sm font-sans font-normal text-slate-700">Smartphone you own</span>
                {{= userRadio(form: $form, input: 'smartphones', fieldset: $home) }}
                {{= userFieldsetError(fieldset: $home, input: 'smartphones') }}
            </label>
        </div>

        <div class="mt-5">
            <h2 class="inline-block text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">Deliveries</h2>
            {{= userFormError(form: $form, input: 'deliveries') }}
            {{ foreach ($form->deliveries as $index => $delivery):  }}
            <div class="mt-5">
                <h2 class="inline-block text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">delivery{{= $index + 1 }}</h2>
                <label class="block mt-5">
                    <span class="block text-sm font-sans font-normal text-slate-700">Zip</span>
                    {{= userText(form: $form, input: 'zip', fieldset: $delivery) }}
                    {{= userFieldsetError(fieldset: $delivery, input: 'zip') }}
                </label>
                <label class="block mt-5">
                    <span class="block text-sm font-sans font-normal text-slate-700">State</span>
                    {{= userText(form: $form, input: 'state', fieldset: $delivery) }}
                    {{= userFieldsetError(fieldset: $delivery, input: 'state') }}
                </label>
                <label class="block mt-5">
                    <span class="block text-sm font-sans font-normal text-slate-700">City</span>
                    {{= userText(form: $form, input: 'city', fieldset: $delivery) }}
                    {{= userFieldsetError(fieldset: $delivery, input: 'city') }}
                </label>
                <label class="block mt-5">
                    <span class="block text-sm font-sans font-normal text-slate-700">Street</span>
                    {{= userText(form: $form, input: 'street', fieldset: $delivery) }}
                    {{= userFieldsetError(fieldset: $delivery, input: 'street') }}
                </label>
                <label class="block mt-5">
                    <span class="block text-sm font-sans font-normal text-slate-700">House type</span>
                    {{= userRadio(form: $form, input: 'houseType', fieldset: $delivery) }}
                    {{= userFieldsetError(fieldset: $delivery, input: 'houseType') }}
                </label>
                <label class="block mt-5">
                    <span class="block text-sm font-sans font-normal text-slate-700">Smartphone you own</span>
                    {{= userRadio(form: $form, input: 'smartphones', fieldset: $delivery) }}
                    {{= userFieldsetError(fieldset: $delivery, input: 'smartphones') }}
                </label>
            </div>
            {{ endforeach }}
        </div>

        <label class="block mt-20">
            <span class="block text-sm font-sans font-normal text-slate-700">Note</span>
            {{= userText(form: $form, input: 'note') }}
            {{= userFormError(form: $form, input: 'note') }}
        </label>
        <label class="block mt-5">
            <span class="block text-sm font-sans font-normal text-slate-700">Agree</span>
            {{= userSelect(form: $form, input: 'agree') }}
            {{= userFormError(form: $form, input: 'agree') }}
        </label>
        <label class="block mt-5 text-center">
            {{= userSubmit(form: $form, input: 'submit', attribs: ['value' => 'SEND']) }}
            {{= csrfTokenField(form: $form) }}
        </label>
    </div>
</form>
