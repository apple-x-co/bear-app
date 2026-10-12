<?php
/** @var Qiq\Engine&Qiq\Helper\Html\HtmlHelpers $this */
$this->setLayout('layout/base');
$formStep = \MyVendor\MyProject\Form\FormStep::class;
?>
<h2>UserPage:ContactDemo</h2><br>
{{ if ($screen->isConfirm()): }}
<div class="mt-5">
    <label class="block">
        <span class="block text-sm font-thin text-slate-500 tracking-wide">Username</span>
        {{h $form->username }}
    </label>
    <div class="mt-5 flex justify-center gap-2">
        <form method="post">
            {{= userHidden(form: $form, input: 'username') }}
            {{= userHidden(form: $form, input: 'step', attribs: ['value' => $formStep::Input->value]) }}
            {{= userSubmit(form: $form, input: 'submit', attribs: ['value' => t('public.contact_demo.back')]) }}
            {{= csrfTokenField(form: $form) }}
        </form>
        <form method="post">
            {{= userHidden(form: $form, input: 'username') }}
            {{= userHidden(form: $form, input: 'step', attribs: ['value' => $formStep::Complete->value]) }}
            {{= userSubmit(form: $form, input: 'submit', attribs: ['value' => t('public.contact_demo.complete')]) }}
            {{= csrfTokenField(form: $form) }}
        </form>
    </div>
</div>
{{ else: }}
<form method="post">
    <div class="mt-5">
        <label class="block">
            <span class="block text-sm font-thin text-slate-500 tracking-wide">Username</span>
            {{= userText(form: $form, input: 'username') }}
            {{= userFormError(form: $form, input: 'username') }}
        </label>
        <label class="block mt-5 text-center">
            {{= userHidden(form: $form, input: 'step', attribs: ['value' => $formStep::Confirm->value]) }}
            {{= userSubmit(form: $form, input: 'submit', attribs: ['value' => t('public.contact_demo.confirm')]) }}
            {{= csrfTokenField(form: $form) }}
        </label>
    </div>
</form>
{{ endif }}
