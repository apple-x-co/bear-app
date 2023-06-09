{{ extends ('layout/Admin/settings') }}

{{ setBlock ('title') }}INDEX | {{ parentBlock () }}{{ endBlock () }}

{{ setBlock ('body_content') }}

{{ if ($message = $this->flashMessage()): }}
{{= render('partials/Admin/AlertInformation', ['text' => $message]) }}
{{ endif }}

{{ endBlock () }}
