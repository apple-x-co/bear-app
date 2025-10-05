{{ extends ('layout/Admin/page') }}

{{ setBlock ('title') }}INDEX | {{ parentBlock () }}{{ endBlock () }}

{{ $this->addData(['isNavVisible' => false]) }}
{{ setBlock ('body_content') }}
{{ endBlock () }}
