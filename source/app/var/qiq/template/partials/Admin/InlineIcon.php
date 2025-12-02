{{ $w = isset($w) ? $w : 6 }}
{{ $h = isset($h) ? $h : 6 }}
{{ $stroke = isset($stroke) ? $stroke : 'stroke-[1.5px]' }}
<svg class="inline w-{{a $w }} h-{{a $h }} {{a $stroke }}">
    <use xlink:href="/admin/images/heroicons/icon.svg#{{a $name }}"></use>
</svg>
