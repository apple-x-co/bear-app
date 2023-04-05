<?php
assert($this instanceof Qiq);
$this->setLayout('layout/base');
?>
<h2>UserPage:Login</h2><br>

<form method="post">
    {{ if (isset($this->authException)): }}
    <p style="color: red; font-weight: bold;">Authentication error</p>
    {{ endif }}
    {{= $this->form->input('username') }}
    {{= $this->form->error('username') }}
    {{= $this->form->input('password') }}
    {{= $this->form->error('password') }}
    {{= $this->form->input('login') }}
    {{= $this->form->input('__csrf_token') }}
</form>
