{{ $w = isset($w) ? $w : 6 }}
{{ $h = isset($h) ? $h : 6 }}
<svg class="inline w-{{= $w }} h-{{= $h }}">
    <use xlink:href="/admin/images/heroicons/icon.svg#{{= $name }}"></use>
</svg>
