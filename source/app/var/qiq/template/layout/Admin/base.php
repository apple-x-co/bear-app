<!doctype html>
<html lang="ja" class="scroll-smooth">
<head>
    <title>{{ setBlock ('title') }}BearApp{{= getBlock () ~}}</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <meta name="theme-color" content="#ffffff">
    <link rel="icon" href="/admin/images/favicon.svg" type="image/svg+xml">
    {{ setBlock ('head') }}{{= getBlock () ~}}
</head>
<body class="bg-slate-50 text-slate-700 tracking-wide selection:bg-fuchsia-300 selection:text-fuchsia-900">
{{ setBlock ('body') }}{{= getBlock () ~}}
</body>
</html>
