<?php

declare(strict_types=1);

return [
    'admin_delete_list.sql' => [],
    'admin_email_by_admin_id.sql' => ['adminId' => 1],
    'admin_item.sql' => ['id' => 1],
//    'admin_item_by_email.sql' => ['emailAddress' => 'admin@example.com'], // NOTE: RuntimeException: Unsupported EXPLAIN format
    'admin_item_by_username.sql' => ['username' => 'admin'],
    'admin_permission_by_admin_id.sql' => ['adminId' => 1],
//    'admin_token_item_by_token.sql' => ['token' => 'abcdefg'], // NOTE: RuntimeException:: Unsupported EXPLAIN format
//    'bad_password_item.sql' => ['password' => 'p@ssw0rd'], // NOTE: RuntimeException: Unsupported EXPLAIN format
    'email_list.sql' => [],
    'email_recipient_list.sql' => ['emailIds' => 1],
    'test_item.sql' => ['id' => 1],
    'test_list.sql' => [],
    'throttle_item_by_key.sql' => ['throttleKey' => 'index'],
//    'verification_code_by_uuid.sql' => ['uuid' => '80fd5900-c337-41f3-a631-307cbb95568c'], // NOTE: RuntimeException: Unsupported EXPLAIN format
];
