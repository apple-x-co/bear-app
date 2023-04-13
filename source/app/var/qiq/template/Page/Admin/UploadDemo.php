{{ extends ('layout/AdminPage/page') }}

{{ setBlock ('title') }}Upload Demo | {{ parentBlock () }}{{ endBlock () }}

{{ $this->navVisible = false }}
{{ setBlock ('body_content') }}
<form method="post" enctype="multipart/form-data">
    <div class="mt-5">
        <label class="block">
            <span class="block text-sm font-thin text-slate-500 tracking-wide">Image file</span>
            {{= AdminFile(form: $this->form, input: 'userFile') }}
            {{= AdminFormError(form: $this->form, input: 'userFile') }}
        </label>
        {{ if (isset($this->uploadedUserFile)): }}
        <div class="block mt-5">
            <p>Client file name: {{h $this->uploadedUserFile->getClientFilename() }}</p>
            <p>Size: {{= $this->uploadedUserFile->getSize() }}</p>
        </div>
        {{ endif }}
        {{ if (isset($this->userFile)): }}
        <div class="block mt-5">
            <p>Client file name: {{h $this->userFile['name'] }}</p>
            <p>Size: {{= $this->userFile['size'] }}</p>
        </div>
        {{ endif }}
        <label class="block mt-5 text-center">
            {{= AdminSubmit(form: $this->form, input: 'submit', attribs: ['value' => 'UPLOAD', 'data-submit-once' => '1']) }}
        </label>
    </div>
</form>
{{ endBlock () }}
