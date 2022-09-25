<?php
assert($this instanceof Qiq);
$this->setLayout('layout/base');
?>
{{ setSection ('nav') }}
<li>home / index</li>
{{ endSection () }}

Greeting: {{h $this->name }}

{{ setSection ('foot') }}
<li>home / index</li>
{{ endSection () }}

{{ prependSection ('foot') }}
<li>pre</li>
{{ endSection () }}

{{ appendSection ('foot') }}
<li>add</li>
{{ endSection () }}
