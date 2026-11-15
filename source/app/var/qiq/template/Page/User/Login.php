<?php
/** @var Qiq\Engine&Qiq\Helper\Html\HtmlHelpers $this */
$this->setLayout('layout/base');
?>
<h2>UserPage:Login</h2><br>

<form method="post">
    {{ if (isset($authException)): }}
    <p style="color: red; font-weight: bold;">{{h t('public.login.error') }}</p>
    {{ endif }}
    {{= formWidget($form, 'username') }}
    {{= formError($form, 'username') }}
    {{= formWidget($form, 'password') }}
    {{= formError($form, 'password') }}
    <p>{{h t('public.login.remember') }} {{= userCheckBox(form: $form, input: 'remember') }}</p>
    {{= formWidget($form, 'login') }}
    {{= formWidget($form, '__csrf_token') }}
</form>
