# bear-app

![SCREENSHOT.png](SCREENSHOT.png)

## Framework, Library

### Backend

* Framework / [BEAR.Sunday](https://github.com/bearsunday/BEAR.Sunday)
* Template Engine / [Qiq](https://github.com/qiqphp/qiq)
* Form / [Aura.Input](https://github.com/auraphp/Aura.Input), [Aura.Filter](https://github.com/auraphp/Aura.Filter)
* Session / [Aura.Session](https://github.com/auraphp/Aura.Session)
* Auth / [Aura.Auth](https://github.com/auraphp/Aura.Auth)
* Router / [Aura.Router](https://github.com/auraphp/Aura.Router)
* Media / [Ray.MediaQuery](https://github.com/ray-di/Ray.MediaQuery)
* AOP / [Ray.AOP](https://github.com/ray-di/Ray.Aop)
* DI / [Ray.Di](https://github.com/ray-di/Ray.Di)
* Env / [env-json](https://github.com/koriym/Koriym.EnvJson)

### Frontend

* CSS / [tailwindcss](https://tailwindcss.com/)
* JavaScript bundler / [Rollup](https://rollupjs.org)

## Form sample

* [Contact](http://localhost/admin/contact-demo)
* [Fieldset](http://localhost/admin/fieldset-demo)
* [Multiple](http://localhost/admin/multiple-demo)
* [Upload](http://localhost/admin/upload-demo)

## Demo

http://localhost/admin/login

```mermaid
graph TD;
  Login-->Index;
  Index-->Settings/Index;
  Settings/Index-->Settings/Emails;
  Settings/Emails-->EmailVerify;
  Settings/Index-->Settings/Password;
  Login-->ForgotPassword;
  ForgotPassword-->CodeVerify;
  CodeVerify-->ResetPassword;
  Login-->Join;
  Join-->CodeVerify;
  CodeVerify-->SignUp;
  Index-->PasswordLock;
  PasswordLock-->Markup;
```

### Features

* ID & password login
* Remember me
* Reset password
* Create account
* Delete account
* Email (immediately or schedule)
* Flash message

### Security

* Cloudflare Turnstile
* Account lock after consecutive failures
* Password confirm when important page visit
* Email notification after password change
* Email authentication
* Email notification when add email address
* Throttling
* Ban common passwords

### Authorization

* control with resource & permission

### Command

* Import bad passwords
* Send scheduled email
