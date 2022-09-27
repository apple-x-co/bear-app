<?php
assert($this instanceof Qiq);
$this->setLayout('layout/base');
?>

{{= form ([
    'method' => 'post',
    'action' => '/hello',
]) }}

<div style="border: 1px solid #efefef; padding: 1em;">
    {{= checkboxField ([
    'name' => 'flags',
    'value' => 'bar',
    '_default' => '',
    '_options' => [
    'foo' => 'Foo Flag',
    'bar' => 'Bar Flag',
    'baz' => 'Baz Flag',
    ]
    ]) }}
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
    {{= colorField ([
    'name' => 'foo',
    'value' => 'bar',
    ]) }}
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
    {{= dateField ([
        'name' => 'foo',
        'value' => 'bar',
    ]) }}
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
    {{= datetimeField ([
        'name' => 'foo',
        'value' => 'bar',
    ]) }}
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
    {{= datetimeLocalField ([
        'name' => 'foo',
        'value' => 'bar',
    ]) }}
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
    {{= emailField ([
        'name' => 'foo',
        'value' => 'bar',
        'placeholder' => 'sample@example.com',
    ]) }}
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
    {{= fileField ([
        'name' => 'foo',
        'value' => 'bar',
    ]) }}
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
    {{= hiddenField ([
        'name' => 'foo',
        'value' => 'bar',
    ]) }}
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
    {{= inputField ([
        'type' => 'text',
        'name' => 'foo',
        'value' => 'bar',
    ]) }}
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
    {{= monthField ([
        'name' => 'foo',
        'value' => 'bar',
    ]) }}
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
    {{= numberField ([
        'type' => 'number',
        'name' => 'foo',
        'value' => 'bar',
    ]) }}
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
    {{= passwordField ([
        'name' => 'foo',
        'value' => 'bar',
    ]) }}
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
    {{= radioField ([
    'name' => 'foo',
    'value' => 'baz',
    '_options' => [
    'bar' => 'Bar Label',
    'baz' => 'Baz Label',
    'dib' => 'Dib Label',
    ],
    ]) }}
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
    {{= rangeField ([
        'name' => 'foo',
        'value' => 'bar',
    ]) }}
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
    {{= searchField ([
        'name' => 'foo',
        'value' => 'bar',
    ]) }}
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
    {{= select ([
        'name' => 'foo',
        'value' => 'dib',
        'placeholder' => 'Please pick one',
        '_default' => '',
        '_options' => [
            'bar' => 'Bar Label',
            'baz' => 'Baz Label',
            'dib' => 'Dib Label',
            'zim' => 'Zim Label',
        ],
    ]) }}
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
    {{= select ([
        'name' => 'foo',
        'value' => 'bar',
        '_options' => [
            'Group A' => [
                'bar' => 'Bar Label',
                'baz' => 'Baz Label',
            ],
            'Group B' => [
                'dib' => 'Dib Label',
                'zim' => 'Zim Label',
            ],
        ],
    ]) }}
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
    {{= telField([
        'name' => 'foo',
        'value' => 'bar',
    ]) }}
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
    {{= textField ([
        'name' => 'foo',
        'value' => 'bar',
    ]) }}
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
    {{= textarea ([
        'name' => 'foo',
        'value' => 'bar',
    ]) }}
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
    {{= timeField ([
        'name' => 'foo',
        'value' => 'bar',
    ]) }}
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
    {{= weekField ([
        'name' => 'foo',
        'value' => 'bar',
    ]) }}
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
    {{= button ([
        'name' => 'foo',
        'value' => 'bar',
    ]) }}
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
    {{= imageButton ([
        'name' => 'foo',
        'src' => 'https://i.pravatar.cc/500',
    ]) }}
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
    {{= submitButton ([
        'name' => 'foo',
        'value' => 'bar',
    ]) }}
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
    {{= resetButton ([
        'name' => 'foo',
        'value' => 'bar',
    ]) }}
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
    {{= label (
        'Label For Field',
        [
            'for' => 'field'
        ]
    ) }}
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
</div>

<div style="border: 1px solid #efefef; padding: 1em;">
</div>



</form>
