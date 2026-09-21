<?php
/** @var Qiq\Engine&Qiq\Helper\Html\HtmlHelpers $this */
$this->setLayout('layout/base');
?>
<h1>{{h t('common_app_name') }}</h1>
<p>Greeting: {{h $greeting }}</p>
{{= doc('hello') }}
