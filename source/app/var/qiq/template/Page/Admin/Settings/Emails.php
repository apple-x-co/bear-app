{{ extends ('layout/Admin/settings') }}

{{ setBlock ('body_content') }}
{{= render ('partials/Admin/Heading1', ['title' => 'Emails']) }}

{{ $adminEmails = $this->admin->emails }}
{{ if (! empty($adminEmails)): }}
<div class="border border-slate-300 rounded-2xl mt-5">
    <ul>
        {{ foreach ($adminEmails as $index0 => $adminEmail): }}
        <li class="flex gap-2 p-4 {{ if ($index0 > 0): }}border-t border-slate-300{{ endif }}">
            <div class="flex-1 col-span-3">
                <p class="text-sm lg:text-base font-bold">{{= $adminEmail->emailAddress }}</p>
                {{ if ($adminEmail->verifiedAt === null): }}
                <p class="text-xs lg:text-sm font-thin">未確認</p>
                {{ else: }}
                <p class="text-xs lg:text-sm font-thin">確認済 {{= $adminEmail->verifiedAt->format('Y-m-d H:i:s') }}</p>
                {{ endif }}
            </div>
            <div class="flex-none text-right">
                <form method="post" action="{{= $this->router->generate('/admin/settings/emails/delete', ['id' => $adminEmail->id]) }}">
                    <button type="submit" value="削除" class="text-rose-500 focus:outline-none">
                        {{= render ('partials/Admin/InlineIcon', ['name' => 'trash']) }}
                    </button>
                </form>
            </div>
        </li>
        {{ endforeach }}
    </ul>
</div>
{{ endif }}

<form method="post">
    <label class="block mt-5 relative">
        <span class="absolute block top-2 left-3 text-sm font-thin text-slate-500 tracking-wide select-none">Email</span>
        {{= AdminText(form: $this->form, input: 'emailAddress') }}
        {{= AdminFormError(form: $this->form, input: 'emailAddress') }}
    </label>
    <label class="block mt-5 text-center">
        {{= AdminSubmit(form: $this->form, input: 'create', attribs: ['value' => 'Create', 'data-submit-once' => '1']) }}
        {{= CsrfTokenField(form: $this->form) }}
    </label>
</form>
{{ endBlock () }}
