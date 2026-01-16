<?php

declare(strict_types=1);

return [
    'admins/admin_delete_list.sql' => [],
    'admins/admin_email_by_admin_id.sql' => ['adminId' => 1],
    'admins/admin_item.sql' => ['id' => 1],
    'admins/admin_item_by_email.sql' => ['emailAddress' => 'admin@example.com'],
    'admins/admin_item_by_username.sql' => ['username' => 'admin'],
    'admins/admin_permission_by_admin_id.sql' => ['adminId' => 1],
    'admins/admin_token_item_by_token.sql' => ['token' => 'abcdefg'],
    'bad_passwords/bad_password_item.sql' => ['password' => 'p@ssw0rd'],
    'email_queues/email_queue_list_by_sendable.sql' => ['scheduleDate' => '2100-01-01 00:00:00'],
    'throttles/throttle_item_by_key.sql' => ['throttleKey' => 'index'],
    'verification_codes/verification_code_by_uuid.sql' => ['uuid' => '80fd5900-c337-41f3-a631-307cbb95568c'],
];
