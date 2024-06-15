{{ extends ('layout/Admin/page') }}

{{ setBlock ('title') }}INDEX | {{ parentBlock () }}{{ endBlock () }}

{{ setBlock ('body_content') }}
<p><a href="markup">Markup</a></p>

<!-- TODO: isAllowed('settings', 'read') -->
<p><a href="settings/">Settings</a></p>
{{ endBlock () }}
