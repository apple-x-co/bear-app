<!doctype html>
<html lang="ja" class="scroll-smooth">
<head>
    <title>{{ setBlock ('title') }}Frooklyn{{= getBlock () ~}}</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <meta name="theme-color" content="#ffffff">
    {{ setBlock ('head_meta') }}{{= getBlock () ~}}
    {{ setBlock ('head_styles') }}
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP:100|Noto+Sans+JP:400|Noto+Sans+JP:700|Noto+Sans+JP:900|Roboto:100|Roboto:400|Roboto:700|Roboto:900&display=swap&subset=japanese" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    {{= getBlock () ~}}
    {{ setBlock ('head_scripts') }}
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body class="bg-slate-50 text-slate-700 tracking-wide selection:bg-fuchsia-300 selection:text-fuchsia-900">
{{= getContent() }}
</body>
</html>
