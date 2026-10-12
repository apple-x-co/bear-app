<?php
/** @var Qiq\Engine&Qiq\Helper\Html\HtmlHelpers $this */
$this->setLayout('layout/base');
?>
<h2>UserPage:MultipleDemo</h2><br>
<form method="post">
    <div class="mt-5">
        <label class="block">
            <span class="block text-sm font-sans font-normal text-slate-700">Fruits</span>
            {{= userCheckBox(form: $form, input: 'fruits') }}
            {{= userFormError(form: $form, input: 'fruits') }}
        </label>
        <label class="block mt-5">
            <span class="block text-sm font-sans font-normal text-slate-700">Primary languages</span>
            {{= userSelect(form: $form, input: 'primaryLanguage') }}
            {{= userFormError(form: $form, input: 'primaryLanguage') }}
        </label>
        <label class="block mt-5">
            <span class="block text-sm font-sans font-normal text-slate-700">Available Languages</span>
            {{= userSelect(form: $form, input: 'languages') }}
            {{= userFormError(form: $form, input: 'languages') }}
        </label>
        <label class="block mt-5">
            <span class="block text-sm font-sans font-normal text-slate-700">Programming Languages</span>
            {{= userSelect(form: $form, input: 'programmingLanguages') }}
            {{= userFormError(form: $form, input: 'programmingLanguages') }}
        </label>
        <label class="block mt-5 text-center">
            {{= userSubmit(form: $form, input: 'submit', attribs: ['value' => 'SEND']) }}
            {{= csrfTokenField(form: $form) }}
        </label>
    </div>
</form>
