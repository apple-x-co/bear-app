{{ extends ('layout/Admin/page') }}

{{ setBlock ('title') }}INDEX | {{ parentBlock () }}{{ endBlock () }}

{{ setBlock ('body_content') }}
<p><a href="markup">Markup</a></p>

{{ if ($this->isAllowed('Settings', 'Read')): }}
<p><a href="settings/">Settings</a></p>
{{ endif }}
{{ endBlock () }}
