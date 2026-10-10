<?php
/** @var Qiq\Engine&Qiq\Helper\Html\HtmlHelpers $this */
$this->setLayout('layout/base');
?>
<h1>DonutCache</h1>
<p>Donut (cached) generated at: {{h $generatedAt }}</p>
{{= $clock }}
