<?php

declare(strict_types=1);

/**
 * @see http://bearsunday.github.io/manuals/1.0/ja/router.html
 * @var \Aura\Router\Map $map
 */

// Admin.Page
// curl http://localhost/admin/index
$map->attach('/admin', '/admin', static function (\Aura\Router\Map $map) {
    $map->tokens([
        'id' => '\d+',
    ])->accepts([
        'text/html',
    ]);


    $map->route('/code-verify', '/code-verify/{uuid}')->tokens(['uuid' => '.+']);
    $map->route('/email-verify', '/email-verify/{signature}')->tokens(['signature' => '.+']);
    $map->route('/reset-password', '/reset-password/{signature}')->tokens(['signature' => '.+']);
    $map->route('/sign-up', '/sign-up/{signature}')->tokens(['signature' => '.+']);
    $map->route('/settings/emails/delete', '/settings/emails/{id}/delete');
});

// Admin.Api
// curl http://localhost/admin/index --header "Accept: application/json"
$map->attach('/admin', '/admin', static function (\Aura\Router\Map $map) {
    $map->tokens([
        'id' => '\d+',
    ])->accepts([
        'application/json',
    ]);
});

// User.Page
// curl http://localhost/user/index
$map->attach('/user', '/user', static function (\Aura\Router\Map $map) {
    $map->tokens([
        'id' => '\d+',
    ])->accepts([
        'text/html',
    ]);
});

// User.Api
// curl http://localhost/user/index --header "Accept: application/json"
$map->attach('/user', '/user', static function (\Aura\Router\Map $map) {
    $map->tokens([
        'id' => '\d+',
    ])->accepts([
        'application/json',
    ]);
});
