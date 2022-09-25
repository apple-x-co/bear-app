<html lang="en">
    <body>
        {{= render ('page/common', []) }}

        {{ if ($this->hasSection('nav')): }}
        <ul style="border: 1px solid #efefef;">
            {{= getSection ('nav') }}
        </ul>
        {{ endif }}

        {{= getContent() }}

        {{ if ($this->hasSection('foot')): }}
        <ul style="border: 1px solid #efefef;">
            {{= getSection ('foot') }}
        </ul>
        {{ endif }}
    </body>
</html>
