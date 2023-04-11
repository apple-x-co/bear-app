{{ extends ('layout/AdminPage/page') }}

{{ setBlock ('title') }}Upload Demo | {{ parentBlock () }}{{ endBlock () }}

{{ $this->navVisible = false }}
{{ setBlock ('body_content') }}
<form method="post" enctype="multipart/form-data">
    <div class="mt-5">
        <label class="block relative">
            <span class="absolute block top-2 left-3 text-sm font-thin text-slate-500 tracking-wide">Image file</span>
            {{= AdminFile($this->form, 'userFile') }}
            {{= AdminFormError($this->form, 'userFile') }}
        </label>
        <label class="block mt-5 text-center">
            {{= AdminSubmit($this->form, 'submit', ['value' => 'UPLOAD', 'data-submit-once' => '1']) }}
        </label>
    </div>
</form>
{{ endBlock () }}
