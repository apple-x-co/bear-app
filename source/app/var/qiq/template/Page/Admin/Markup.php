{{ extends ('layout/Admin/page') }}

{{ setBlock ('title') }}MARKUP | {{ parentBlock () }}{{ endBlock () }}

{{ $this->addData(['navVisible' => false]) }}
{{ setBlock ('body_content') }}
<section>
    <h3 class="font-bold border-b pb-2 mb-2">テキスト (太さ)</h3>

    <p class="text-sm lg:text-base font-thin">フォント太さ 細字 (100)</p>
    <p class="text-sm lg:text-base font-normal">フォント太さ 通常 (400)</p>
    <p class="text-sm lg:text-base font-bold">フォント太さ 太字 (700)</p>
    <p class="text-sm lg:text-base font-extrabold">フォント太さ 極太 (800)</p>
</section>

<section class="mt-10">
    <h3 class="font-bold border-b pb-2 mb-2">テキスト (行の高さ)</h3>

    <p class="text-sm lg:text-base/relaxed">説明テキスト (relaxed) </p>
    <p class="text-sm lg:text-base/normal">説明テキスト (normal)</p>
    <p class="text-sm lg:text-base/loose">説明テキスト (loose)</p>
</section>

<section class="mt-10">
    <h3 class="font-bold border-b pb-2 mb-2">テキスト (装飾)</h3>

    <p class="text-sm lg:text-base font-thin">下線<span class="underline decoration-8 decoration-sky-500/80 underline-offset-[-5px]">テキスト</span></p>
</section>

<section class="mt-10">
    <h3 class="font-bold border-b pb-2 mb-2">アイコン</h3>

    {{= render('partials/Admin/InlineIcon', ['name' => 'exclamation-circle']) }}
    {{= render('partials/Admin/InlineIcon', ['name' => 'exclamation-triangle']) }}
    {{= render('partials/Admin/InlineIcon', ['name' => 'shield-exclamation']) }}
    {{= render('partials/Admin/InlineIcon', ['name' => 'arrow-left-on-rectangle']) }}
</section>

<section class="mt-10">
    <h3 class="font-bold border-b pb-2 mb-2">見出し1</h3>

    {{= render('partials/Admin/Heading1', ['title' => 'Title タイトル']) }}
</section>

<section class="mt-10">
    <h3 class="font-bold border-b pb-2 mb-2">見出し2</h3>

    {{= render('partials/Admin/Heading2', ['title' => 'Title タイトル', 'subtitle' => 'Subtitle サブタイトル']) }}
</section>

<section class="mt-10">
    <h3 class="font-bold border-b pb-2 mb-2">テーブル</h3>

    <table class="table-auto w-full border-collapse border border-slate-400 rounded-3xl">
        <caption class="text-sm caption-bottom">
            Table 3.1: Professional wrestlers and their signature moves.
        </caption>
        <thead class="bg-slate-100">
        <tr>
            <th class="border border-slate-300 p-4 text-sm lg:text-base">Song</th>
            <th class="border border-slate-300 p-4 text-sm lg:text-base">Artist</th>
            <th class="border border-slate-300 p-4 text-sm lg:text-base">Year</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="border border-slate-300 p-4 text-sm lg:text-base">The Sliding Mr. Bones (Next Stop, Pottersville)</td>
            <td class="border border-slate-300 p-4 text-sm lg:text-base">Malcolm Lockyer</td>
            <td class="border border-slate-300 p-4 text-sm lg:text-base">1961</td>
        </tr>
        <tr>
            <td class="border border-slate-300 p-4 text-sm lg:text-base">Witchy Woman</td>
            <td class="border border-slate-300 p-4 text-sm lg:text-base">The Eagles</td>
            <td class="border border-slate-300 p-4 text-sm lg:text-base">1972</td>
        </tr>
        <tr>
            <td class="border border-slate-300 p-4 text-sm lg:text-base">Shining Star</td>
            <td class="border border-slate-300 p-4 text-sm lg:text-base">Earth, Wind, and Fire</td>
            <td class="border border-slate-300 p-4 text-sm lg:text-base">1975</td>
        </tr>
        </tbody>
    </table>
</section>

<section class="mt-10">
    <h3 class="font-bold border-b pb-2 mb-2">リスト</h3>

    <div class="border border-slate-300 rounded-2xl">
        <ul>
            <li class="border-b border-slate-300 p-4">
                <p class="text-xs lg:text-sm font-bold">見出し</p>
                <p class="text-xs lg:text-sm">テキスト テキスト テキスト</p>
            </li>
            <li class="border-b border-slate-300 p-4">
                <p class="text-xs lg:text-sm font-bold">見出し</p>
                <p class="text-xs lg:text-sm">テキスト テキスト テキスト</p>
            </li>
            <li class="flex gap-2 p-4">
                <div class="flex-none">
                    {{= render('partials/Admin/InlineIcon', ['name' => 'bell-alert']) }}
                </div>
                <div class="flex-1 col-span-3">
                    <p class="text-xs lg:text-sm font-bold">見出し</p>
                    <p class="text-xs lg:text-sm">テキスト テキスト テキスト</p>
                </div>
                <div class="flex-none text-right">
                    <input type="submit" value="実行" class="py-1 px-2 bg-lime-500 text-white text-sm font-sans font-bold tracking-wider rounded-md shadow-lg shadow-lime-500/50 focus:outline-none">
                </div>
            </li>
        </ul>
    </div>
</section>

<section class="mt-10">
    <h3 class="font-bold border-b pb-2 mb-2">アラート</h3>

    {{= render('partials/Admin/AlertInformation', ['text' => 'Information']) }}
    {{= render('partials/Admin/AlertWarning', ['text' => 'Warning']) }}
    {{= render('partials/Admin/AlertError', ['text' => 'Error']) }}
</section>

<section class="mt-10">
    <h3 class="font-bold border-b pb-2 mb-2">フォーム</h3>

    <form>
        <label class="block mt-5">
            <span class="block text-sm font-sans font-normal text-slate-700">項目名テキスト <span class="text-gray-400 italic">- 任意</span></span>
            <span class="block text-sm text-slate-500 font-thin italic">注釈テキスト</span>
            <span class="block text-sm text-rose-500 italic">エラーメッセージテキスト</span>
        </label>

        <label class="block mt-5">
            <span class="block text-sm font-sans font-normal text-slate-700">テキストタイプ</span>
            <input type="text" class="rounded w-full placeholder:text-slate-500 placeholder:font-thin focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500" placeholder="プレースホルダー">
        </label>

        <label class="block mt-5">
            <span class="absolute block top-2 left-3 text-sm font-thin text-slate-500">テキストタイプ</span>
            <input type="text" class="rounded w-full pt-7 placeholder:text-slate-500 placeholder:font-thin focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500" placeholder="">
        </label>

        <label class="block mt-5">
            <span class="block text-sm font-sans font-normal text-slate-700">テキストエリア</span>
            <textarea class="rounded w-full placeholder:text-slate-500 placeholder:font-thin focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500 leading-6 h-40" placeholder="プレースホルダー"></textarea>
        </label>

        <label class="block mt-5">
            <span class="absolute block top-2 left-3 text-sm font-thin text-slate-500">テキストエリア</span>
            <textarea class="rounded w-full pt-7 placeholder:text-slate-500 placeholder:font-thin focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500 leading-6 h-40" placeholder=""></textarea>
        </label>

        <label class="block mt-5">
            <span class="block text-sm font-sans font-normal text-slate-700">日付</span>
            <input type="text" class="flatpickr1 rounded w-full placeholder:text-slate-500 placeholder:font-thin focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500" placeholder="YYYY-MM-DD">
            <script>flatpickr('.flatpickr1', {disableMobile: true});</script>
        </label>

        <label class="block mt-5">
            <span class="absolute block top-2 left-3 text-sm font-thin text-slate-500">日付</span>
            <input type="text" class="flatpickr3 rounded w-full pt-7 placeholder:text-slate-500 placeholder:font-thin focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500" placeholder="YYYY-MM-DD">
            <script>flatpickr('.flatpickr3', {disableMobile: true});</script>
        </label>

        <label class="block mt-5">
            <span class="block text-sm font-sans font-normal text-slate-700">日時</span>
            <input type="text" class="flatpickr2 rounded w-full placeholder:text-slate-500 placeholder:font-thin focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500" placeholder="YYYY-MM-DD HH:MM">
            <script>flatpickr('.flatpickr2', {disableMobile: true, enableTime: true, dateFormat: "Y-m-d H:i"});</script>
        </label>

        <label class="block mt-5">
            <span class="block top-2 left-3 text-sm font-thin text-slate-500">日時</span>
            <input type="text" class="flatpickr4 rounded w-full pt-7 placeholder:text-slate-500 placeholder:font-thin focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500" placeholder="YYYY-MM-DD HH:MM">
            <script>flatpickr('.flatpickr4', {disableMobile: true, enableTime: true, dateFormat: "Y-m-d H:i"});</script>
        </label>

        <label class="block mt-5">
            <span class="block top-2 left-3 text-sm font-normal text-slate-700 tracking-wide select-none">ファイルタイプ</span>
            <input type="file" class="rounded w-full p-3 pt-7 bg-white border border-[#6b7280] placeholder:text-slate-500 placeholder:font-thin focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500" placeholder="">
        </label>

        <div class="mt-5">
            <span class="block text-sm font-sans font-normal text-slate-700">チェックボックス</span>
            <label class="block">
                <input type="checkbox" name="check1" value="1">
                <span class="text-base font-sans font-normal text-slate-700">チェック1</span>
            </label>
            <label class="block">
                <input type="checkbox" name="check2" value="2">
                <span class="text-base font-sans font-normal text-slate-700">チェック2</span>
            </label>
        </div>

        <div class="mt-5">
            <span class="block text-sm font-sans font-normal text-slate-700">ラジオボタン</span>
            <label class="inline">
                <input type="radio" name="radio" value="1">
                <span class="text-base font-sans font-normal text-slate-700">ラジオ1</span>
            </label>
            <label class="inline ml-2">
                <input type="radio" name="radio" value="2">
                <span class="text-base font-sans font-normal text-slate-700">ラジオ2</span>
            </label>
        </div>

        <label class="block mt-5">
            <span class="block text-sm font-sans font-normal text-slate-700">選択（選択肢が6個以上）</span>
            <select class="rounded focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500">
                <option>選択肢1</option>
                <option>選択肢2</option>
                <option>選択肢3</option>
            </select>
        </label>

        <label class="block mt-5">
            <span class="absolute block top-2 left-3 text-sm font-thin text-slate-500">選択（選択肢が6個以上）</span>
            <select class="rounded pt-7 focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500">
                <option>選択肢1</option>
                <option>選択肢2</option>
                <option>選択肢3</option>
            </select>
        </label>

        <div class="mt-5">
            <span class="block text-sm font-sans font-normal text-slate-700">送信ボタン</span>
            <input type="submit" value="登録" class="py-2 px-3 bg-sky-500 text-white text-sm font-sans font-bold tracking-wider rounded-md shadow-lg shadow-sky-500/50 focus:outline-none">
        </div>

        <div class="mt-5">
            <span class="block text-sm font-sans font-normal text-slate-700">キャンセルボタン</span>
            <input type="button" value="キャンセル" class="py-2 px-3 bg-white text-gray-500 text-sm font-sans font-bold tracking-wider rounded-md shadow-lg shadow-gray-500/20 focus:outline-none border border-gray-300">
        </div>

        <div class="mt-5">
            <span class="block text-sm font-sans font-normal text-slate-700">無効ボタン</span>
            <input type="button" value="キャンセル" class="py-2 px-3 bg-sky-500 text-white text-sm font-sans font-bold tracking-wider rounded-md shadow-lg shadow-sky-500/50 focus:outline-none disabled:text-white disabled:bg-slate-200 disabled:shadow-none" disabled="disabled">
        </div>

        <div class="mt-5">
            <div class="border-2 border-rose-500 bg-rose-100 rounded-md p-5">
                <p class="text-sm mb-1 font-semibold">削除しますか?</p>
                <p class="text-xs mb-3">削除後は元に戻すことができません</p>
                <button type="submit" value="削除" class="py-2 px-4 bg-rose-500 text-white text-sm font-sans font-bold tracking-wider rounded-full shadow-lg shadow-rose-500/50 focus:outline-none hover:bg-rose-600 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none">
                    {{= render('partials/Admin/InlineIcon', ['name' => 'trash']) }}
                    削除する
                </button>
            </div>
        </div>
    </form>
</section>

<section class="mt-10">
    <h3 class="font-bold border-b pb-2 mb-2">ナビゲーション</h3>

    <nav class="block leading-6 relative">
        <p>
            <a class="text-sm font-bold tracking-wider text-sky-500" href="">HOME</a>
        </p>
        <ul class="mt-5">
            <li>
                <h5 class="font-sans font-bold text-sm tracking-wide mb-8 lg:mb-3">Title タイトル</h5>
                <ul class="space-y-6 lg:space-y-2 border-l border-slate-200">
                    <li><a href="" class="text-sm tracking-wide pl-4 -ml-px border-l font-bold text-sky-500 border-l border-current">メニュー1</a></li>
                    <li><a href="" class="text-sm tracking-wide pl-4 -ml-px border-l border-transparent hover:border-slate-400">メニュー2</a></li>
                    <li><a href="" class="text-sm tracking-wide pl-4 -ml-px border-l border-transparent hover:border-slate-400">メニュー3</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</section>

<section class="mt-10">
    <h3 class="font-bold border-b pb-2 mb-2">Aside</h3>
    <!-- FIXME -->
    <aside></aside>
</section>
{{ endBlock () }}
