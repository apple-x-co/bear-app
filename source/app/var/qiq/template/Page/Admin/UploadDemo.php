{{ extends ('layout/AdminPage/page') }}

{{ setBlock ('title') }}Upload Demo | {{ parentBlock () }}{{ endBlock () }}

{{ $this->navVisible = false }}
{{ setBlock ('body_content') }}
<form method="post" enctype="multipart/form-data">
    <div class="mt-5">
        <label class="block">
            <span class="block text-sm font-thin text-slate-500 tracking-wide">Image file</span>
            {{= AdminFile(form: $this->form, input: 'file') }}
            {{= AdminFormError(form: $this->form, input: 'file') }}
        </label>
        {{ if (isset($this->uploadedFile)): }}
        <div class="block mt-5">
            <p class="font-bold">UploadedFileInterface</p>
            <p>Client file name: {{h $this->uploadedFile->getClientFilename() }}</p>
            <p>Client media type: {{h $this->uploadedFile->getClientMediaType() }}</p>
            <p>Size: {{= $this->uploadedFile->getSize() }}</p>
        </div>
        {{ endif }}
        {{ if (isset($this->file)): }}
        <div class="block mt-5">
            <p class="font-bold">$_FILES</p>
            <p>Name: {{h $this->file['name'] }}</p>
            <p>Type: {{= $this->file['type'] }}</p>
            <p>Size: {{= $this->file['size'] }}</p>
        </div>
        {{ endif }}
        <label class="block mt-5 text-center">
            {{= AdminSubmit(form: $this->form, input: 'submit', attribs: ['value' => 'UPLOAD', 'data-submit-once' => '1']) }}
        </label>
    </div>
</form>
{{ endBlock () }}
