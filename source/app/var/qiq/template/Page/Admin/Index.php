{{ extends ('layout/Admin/page') }}

{{ setBlock ('title') }}INDEX | {{ parentBlock () }}{{ endBlock () }}

{{ $this->addData(['navVisible' => false]) }}
{{ setBlock ('body_content') }}
{{ endBlock () }}
