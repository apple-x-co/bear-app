<?php
assert($this instanceof Qiq);
$this->setLayout('layout/base');
?>
{{ setSection ('nav') }}
<li>home / index</li>
{{ endSection () }}

{{ setSection ('foot') }}
<li>home / index</li>
{{ endSection () }}

{{ prependSection ('foot') }}
<li>pre</li>
{{ endSection () }}

{{ appendSection ('foot') }}
<li>add</li>
{{ endSection () }}

<div style="border: 1px solid #dddddd;">
    Greeting: {{h $this->name }}

    <p>My List</p>
    {{= render ('page/list', [
    'items' => ['foo', 'bar', 'baz']
    ]) }}
</div>
