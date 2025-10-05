{{ extends ('layout/Admin/settings') }}

{{ setBlock ('title') }}INDEX | {{ parentBlock () }}{{ endBlock () }}

{{ $this->addData(['isNavVisible' => true]) }}
{{ setBlock ('body_content') }}

{{ if ($message = $context->flashMessage()): }}
{{= render('partials/Admin/AlertInformation', ['text' => $message]) }}
{{ endif }}

{{ endBlock () }}
