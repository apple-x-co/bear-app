{{ extends ('layout/Admin/settings') }}

{{ setBlock ('title') }}INDEX | {{ parentBlock () }}{{ endBlock () }}

{{ setBlock ('body_content') }}

<!-- TODO: Get FlashMessage by resourceObject -->
{{= render('partials/Admin/AlertInformation', ['text' => 'FIXME']) }}

{{ endBlock () }}
