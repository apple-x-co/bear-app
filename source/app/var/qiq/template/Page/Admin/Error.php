{{ extends ('layout/Admin/base') }}

{{ setBlock ('body') }}
<div class="h-[100svh] grid place-content-center">
    <div class="relative w-80 md:w-96 h-min p-5 md:p-8 rounded-xl bg-white shadow-[0_1px_3px_rgba(15,23,42,0.03),0_1px_2px_rgba(15,23,42,0.06)] ring-1 ring-slate-600/[0.04]">
        <h2 class="text-xl text-center tracking-widest font-sans font-bold text-rose-500">Error</h2>

        <div class="mt-5">
            <p class="text-rose-500 font-semibold">{{= $this->message }}</p>
        </div>
        {{ if ($this->returnUrl !== null): }}
        <div class="mt-5 text-center">
            <a href="{{= $this->returnUrl }}" class="font-bold">return "{{= $this->returnName }}"</a>
        </div>
        {{ endif }}
    </div>
</div>
{{ endBlock () }}
