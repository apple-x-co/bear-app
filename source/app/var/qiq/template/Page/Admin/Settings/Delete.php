{{ extends ('layout/Admin/settings') }}

{{ setBlock ('body') }}
<div class="h-[100svh] grid place-content-center">
    <div class="relative w-80 md:w-96 h-min p-5 md:p-8 rounded-xl bg-white shadow-[0_1px_3px_rgba(15,23,42,0.03),0_1px_2px_rgba(15,23,42,0.06)] ring-1 ring-slate-600/[0.04]">
        <h2 class="text-xl text-center tracking-widest font-sans font-bold">Delete account</h2>

        <form method="post">
            <div class="mt-5">
                <label class="block mt-5 text-center">
                    {{= adminSubmit(form: $form, input: 'continue', attribs: ['value' => 'Delete your account', 'data-submit-once' => '1']) }}
                    {{= csrfTokenField(form: $form) }}
                </label>
            </div>
        </form>
        <div class="absolute top-full right-0 w-full h-px rounded-full max-w-sm bg-gradient-to-r from-transparent from-10% via-purple-500 to-transparent drop-shadow-xl"></div>
    </div>
    <p class="text-sm text-center mt-2"><a href="{{= url('/admin/settings/') }}">Back</a></p>
</div>
{{ endBlock () }}
