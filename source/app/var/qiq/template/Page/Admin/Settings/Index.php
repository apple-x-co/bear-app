{{ extends ('layout/Admin/settings') }}

{{ setBlock ('title') }}INDEX | {{ parentBlock () }}{{ endBlock () }}

{{ setBlock ('body_content') }}

{{ if ($flashMessage = $this->session->getFlashMessage()): }}
{{= render ('partials/Admin/AlertInformation', ['text' => $flashMessage]) }}
{{ endif }}

{{ endBlock () }}
