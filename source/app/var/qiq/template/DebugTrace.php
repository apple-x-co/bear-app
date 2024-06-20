<?php
assert($this instanceof \Qiq\Template);
$this->setLayout('layout/base');
?>
<div class="max-w-7xl mx-auto px-0 lg:px-8 pt-5 flex flex-row grow">
    <div class="block lg:flex lg:flex-col lg:grow w-full">
        <div class="mx-5 lg:mx-0 lg:grow whitespace-normal break-words">
            <main>
                <div class="h-[100svh] grid place-content-center">
                    <div class="relative w-80 md:w-96 h-min p-5 md:p-8 rounded-xl bg-white shadow-[0_1px_3px_rgba(15,23,42,0.03),0_1px_2px_rgba(15,23,42,0.06)] ring-1 ring-slate-600/[0.04]">
                        <h2 class="text-xl text-center tracking-widest font-sans font-bold text-rose-500">Exception</h2>
                        <div class="mt-2 text-center">
                            <p class="text-rose-500 font-semibold">{{h $e['code'] }}</p>
                            <p class="text-rose-500 font-semibold">{{h $e['message'] }}</p>
                            <p class="text-rose-500 font-semibold">{{h $e['class'] }}</p>
                            <p class="text-rose-500 font-semibold">{{h $e['file'] }}#L{{h $e['line'] }}</p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
