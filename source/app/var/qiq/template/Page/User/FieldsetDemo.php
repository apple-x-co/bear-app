<?php
/** @var Qiq\Engine&Qiq\Helper\Html\HtmlHelpers $this */
$this->setLayout('layout/base');
?>
<h2>UserPage:FieldsetDemo</h2><br>
<form method="post">
    <div class="mt-5">
        <div class="mt-5">
            <h2 class="inline-block text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">Home</h2>
            {{ $addressFieldset = $form->home }}
            <label class="block mt-5">
                <span class="block text-sm font-sans font-normal text-slate-700">Zip</span>
                {{= userText(form: $form, input: 'zip', fieldset: $addressFieldset) }}
                {{= userFieldsetError(fieldset: $addressFieldset, input: 'zip') }}
            </label>
            <label class="block mt-5">
                <span class="block text-sm font-sans font-normal text-slate-700">State</span>
                {{= userText(form: $form, input: 'state', fieldset: $addressFieldset) }}
                {{= userFieldsetError(fieldset: $addressFieldset, input: 'state') }}
            </label>
            <label class="block mt-5">
                <span class="block text-sm font-sans font-normal text-slate-700">City</span>
                {{= userText(form: $form, input: 'city', fieldset: $addressFieldset) }}
                {{= userFieldsetError(fieldset: $addressFieldset, input: 'city') }}
            </label>
            <label class="block mt-5">
                <span class="block text-sm font-sans font-normal text-slate-700">Street</span>
                {{= userText(form: $form, input: 'street', fieldset: $addressFieldset) }}
                {{= userFieldsetError(fieldset: $addressFieldset, input: 'street') }}
            </label>
            <label class="block mt-5">
                <span class="block text-sm font-sans font-normal text-slate-700">House type</span>
                {{= userRadio(form: $form, input: 'houseType', fieldset: $addressFieldset) }}
                {{= userFieldsetError(fieldset: $addressFieldset, input: 'houseType') }}
            </label>
            <label class="block mt-5">
                <span class="block text-sm font-sans font-normal text-slate-700">Smartphone you own</span>
                {{= userRadio(form: $form, input: 'smartphones', fieldset: $addressFieldset) }}
                {{= userFieldsetError(fieldset: $addressFieldset, input: 'smartphones') }}
            </label>
        </div>

        <div class="mt-5">
            <h2 class="inline-block text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">Deliveries</h2>
            {{ foreach ($form->deliveries as $index => $addressFieldset):  }}
            <div class="mt-5">
                <h2 class="inline-block text-xl sm:text-2xl font-extrabold text-slate-800 tracking-tight">delivery{{= $index + 1 }}</h2>
                <label class="block mt-5">
                    <span class="block text-sm font-sans font-normal text-slate-700">Zip</span>
                    {{= userText(form: $form, input: 'zip', fieldset: $addressFieldset) }}
                    {{= userFieldsetError(fieldset: $addressFieldset, input: 'zip') }}
                </label>
                <label class="block mt-5">
                    <span class="block text-sm font-sans font-normal text-slate-700">State</span>
                    {{= userText(form: $form, input: 'state', fieldset: $addressFieldset) }}
                    {{= userFieldsetError(fieldset: $addressFieldset, input: 'state') }}
                </label>
                <label class="block mt-5">
                    <span class="block text-sm font-sans font-normal text-slate-700">City</span>
                    {{= userText(form: $form, input: 'city', fieldset: $addressFieldset) }}
                    {{= userFieldsetError(fieldset: $addressFieldset, input: 'city') }}
                </label>
                <label class="block mt-5">
                    <span class="block text-sm font-sans font-normal text-slate-700">Street</span>
                    {{= userText(form: $form, input: 'street', fieldset: $addressFieldset) }}
                    {{= userFieldsetError(fieldset: $addressFieldset, input: 'street') }}
                </label>
                <label class="block mt-5">
                    <span class="block text-sm font-sans font-normal text-slate-700">House type</span>
                    {{= userRadio(form: $form, input: 'houseType', fieldset: $addressFieldset) }}
                    {{= userFieldsetError(fieldset: $addressFieldset, input: 'houseType') }}
                </label>
                <label class="block mt-5">
                    <span class="block text-sm font-sans font-normal text-slate-700">Smartphone you own</span>
                    {{= userRadio(form: $form, input: 'smartphones', fieldset: $addressFieldset) }}
                    {{= userFieldsetError(fieldset: $addressFieldset, input: 'smartphones') }}
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
