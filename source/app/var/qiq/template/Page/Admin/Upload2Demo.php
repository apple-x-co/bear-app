{{ extends ('layout/Admin/page') }}

{{ setBlock ('title') }}Upload2 Demo | {{ parentBlock () }}{{ endBlock () }}

{{ $this->navVisible = false }}
{{ setBlock ('body_content') }}
<form method="post" enctype="multipart/form-data">
    {{ if ($this->form->mode == $this->confirmMode->name): }}
    <div class="mt-5">
        <label class="block">
            <span class="block text-sm font-thin text-slate-500 tracking-wide">Image file</span>
            {{ $fileset = $this->form->fileset }}
            {{= $fileset->clientFileName }} (MediaType:{{= $fileset->clientMediaType }}, Size:{{= $fileset->size }})
            {{= AdminHidden(form: $this->form, fieldset: $fileset, input: 'clientFileName') }}
            {{= AdminHidden(form: $this->form, fieldset: $fileset, input: 'clientMediaType') }}
            {{= AdminHidden(form: $this->form, fieldset: $fileset, input: 'size') }}
            {{= AdminHidden(form: $this->form, fieldset: $fileset, input: 'tmpName') }}
        </label>
        <label class="block mt-5 text-center">
            {{= AdminSubmit(form: $this->form, input: 'mode', attribs: ['value' => $this->inputMode->name, 'data-submit-once' => '1']) }}
            {{= AdminSubmit(form: $this->form, input: 'mode', attribs: ['value' => $this->completeMode->name, 'data-submit-once' => '1']) }}
            {{= CsrfTokenField(form: $this->form) }}
        </label>
    </div>
    {{ else: }}
    <div class="mt-5">
        <label class="block">
            <span class="block text-sm font-thin text-slate-500 tracking-wide">Image file</span>
            {{ $fileset = $this->form->fileset }}
            {{h AdminFile(form: $this->form, fieldset: $fileset, input: 'file') }}
            {{ $clientFileName = $fileset->clientFileName }}
            {{ if ($clientFileName !== null): }}
            <span class="block text-sm">選択中のファイル：{{h $clientFileName }}</span>
            {{ endif }}
            {{= AdminFormError(form: $this->form, input: 'fileset') }}
            {{= AdminFieldsetError(fieldset: $fileset, input: 'file') }}
            {{= AdminHidden(form: $this->form, fieldset: $fileset, input: 'clientFileName') }}
            {{= AdminHidden(form: $this->form, fieldset: $fileset, input: 'clientMediaType') }}
            {{= AdminHidden(form: $this->form, fieldset: $fileset, input: 'size') }}
            {{= AdminHidden(form: $this->form, fieldset: $fileset, input: 'tmpName') }}
        </label>
        <label class="block mt-5 text-center">
            {{= AdminSubmit(form: $this->form, input: 'mode', attribs: ['value' => $this->confirmMode->name, 'data-submit-once' => '1']) }}
            {{= CsrfTokenField(form: $this->form) }}
        </label>
    </div>
    {{ endif }}
</form>
{{ endBlock () }}
