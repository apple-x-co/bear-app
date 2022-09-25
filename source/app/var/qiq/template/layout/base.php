<html lang="en">
    <body>
        {{= render ('page/common', []) }}

        {{ if ($this->hasSection('nav')): }}
            <ul>
                {{= getSection ('nav') }}
            </ul>
        {{ endif }}

        {{= getContent() }}

        {{ if ($this->hasSection('foot')): }}
            <ul>
                {{= getSection ('foot') }}
            </ul>
        {{ endif }}
    </body>
</html>
