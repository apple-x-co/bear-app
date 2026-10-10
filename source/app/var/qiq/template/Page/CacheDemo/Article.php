<?php
/** @var Qiq\Engine&Qiq\Helper\Html\HtmlHelpers $this */
$this->setLayout('layout/base');
?>
<h1>CacheableResponse</h1>
<p>Article (#[CacheableResponse]) generated at: {{h $generatedAt }}</p>
{{= $comment }}
