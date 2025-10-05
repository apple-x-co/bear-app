{{ extends ('layout/Admin/page') }}

{{ setBlock ('title') }}Upload2 Demo | {{ parentBlock () }}{{ endBlock () }}

{{ setBlock ('body_content') }}
<form method="post" enctype="multipart/form-data">
    {{ if ($form->mode == $confirmMode->name): }}
    <div class="mt-5">
        <label class="block">
            <span class="block text-sm font-thin text-slate-500 tracking-wide">Image file</span>
            {{ $fileset = $form->fileset }}
            {{= $fileset->clientFileName }} (MediaType:{{= $fileset->clientMediaType }}, Size:{{= $fileset->size }})
            {{= adminHidden(form: $form, fieldset: $fileset, input: 'clientFileName') }}
            {{= adminHidden(form: $form, fieldset: $fileset, input: 'clientMediaType') }}
            {{= adminHidden(form: $form, fieldset: $fileset, input: 'size') }}
            {{= adminHidden(form: $form, fieldset: $fileset, input: 'tmpName') }}
        </label>
        <label class="block mt-5 text-center">
            {{= adminSubmit(form: $form, input: 'mode', attribs: ['value' => $inputMode->name, 'data-submit-once' => '1']) }}
            {{= adminSubmit(form: $form, input: 'mode', attribs: ['value' => $completeMode->name, 'data-submit-once' => '1']) }}
            {{= csrfTokenField(form: $form) }}
        </label>
    </div>
    {{ else: }}
    <div class="mt-5">
        <label class="block">
            <span class="block text-sm font-thin text-slate-500 tracking-wide">Image file</span>
            {{ $fileset = $form->fileset }}
            {{= adminFile(form: $form, fieldset: $fileset, input: 'file') }}
            {{ $clientFileName = $fileset->clientFileName }}
            {{ if ($clientFileName !== null): }}
            <span class="block text-sm">選択中のファイル：{{h $clientFileName }}</span>
            {{ endif }}
            {{= adminFormError(form: $form, input: 'fileset') }}
            {{= adminFieldsetError(fieldset: $fileset, input: 'file') }}
            {{= adminHidden(form: $form, fieldset: $fileset, input: 'clientFileName') }}
            {{= adminHidden(form: $form, fieldset: $fileset, input: 'clientMediaType') }}
            {{= adminHidden(form: $form, fieldset: $fileset, input: 'size') }}
            {{= adminHidden(form: $form, fieldset: $fileset, input: 'tmpName') }}
        </label>
        <label class="block mt-5 text-center">
            {{= adminSubmit(form: $form, input: 'mode', attribs: ['value' => $confirmMode->name, 'data-submit-once' => '1']) }}
            {{= csrfTokenField(form: $form) }}
        </label>
    </div>
    {{ endif }}
</form>
{{ endBlock () }}
