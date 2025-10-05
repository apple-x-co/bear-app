{{ extends ('layout/Admin/base') }}

{{ setBlock ('head') }}
{{ setBlock ('head_meta') }}{{= getBlock () ~}}
{{ setBlock ('head_styles') }}
<link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP:100|Noto+Sans+JP:400|Noto+Sans+JP:700|Noto+Sans+JP:900|Roboto:100|Roboto:400|Roboto:700|Roboto:900&display=swap&subset=japanese" rel="stylesheet">
<link href="/admin/css/bundle.css" rel="stylesheet">
<link href="/admin/css/tailwind.css" rel="stylesheet">
{{= getBlock () ~}}
{{ setBlock ('head_scripts') }}
<script src="/admin/js/bundle.min.js"></script>
{{= getBlock () ~}}
{{ endBlock () }}

{{ setBlock ('body') }}
{{ setBlock ('body_header') }}
{{= getBlock () ~}}
<main>{{ setBlock ('body_content') }}{{= getBlock () ~}}</main>
<footer>{{ setBlock ('body_footer') }}<p class="font-sans font-thin text-sm">Copyright &copy; 2023 apple-x-co.</p>{{= getBlock () ~}}</footer>
{{ endBlock () }}
