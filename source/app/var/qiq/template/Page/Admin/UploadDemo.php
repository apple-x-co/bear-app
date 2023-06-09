{{ extends ('layout/Admin/page') }}

{{ setBlock ('title') }}Upload Demo | {{ parentBlock () }}{{ endBlock () }}

{{ $this->addData(['navVisible' => false]) }}
{{ setBlock ('body_content') }}
<form method="post" enctype="multipart/form-data">
    <div class="mt-5">
        <label class="block">
            <span class="block text-sm font-thin text-slate-500 tracking-wide">Image file</span>
            {{= adminFile(form: $form, input: 'file') }}
            {{= adminFormError(form: $form, input: 'file') }}
        </label>
        {{ if (isset($uploadedFile)): }}
        <div class="block mt-5">
            <p class="font-bold">UploadedFileInterface</p>
            <p>Client file name: {{h $uploadedFile->getClientFilename() }}</p>
            <p>Client media type: {{h $uploadedFile->getClientMediaType() }}</p>
            <p>Size: {{= $uploadedFile->getSize() }}</p>
        </div>
        {{ endif }}
        {{ if (isset($file)): }}
        <div class="block mt-5">
            <p class="font-bold">$_FILES</p>
            <p>Name: {{h $file['name'] }}</p>
            <p>Type: {{= $file['type'] }}</p>
            <p>Size: {{= $file['size'] }}</p>
        </div>
        {{ endif }}
        <label class="block mt-5 text-center">
            {{= adminSubmit(form: $form, input: 'submit', attribs: ['value' => 'UPLOAD', 'data-submit-once' => '1']) }}
        </label>
    </div>
</form>
{{ endBlock () }}
