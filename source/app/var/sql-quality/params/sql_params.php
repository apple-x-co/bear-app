<?php

declare(strict_types=1);

/**
 * SQL Parameters
 * 
 * Auto-generated parameter bindings for SQL files.
 * Generated at: 2026-01-16 11:29:36
 */

return [
    'admins/admin_add.sql' => [
        'username' => 'Test Name',
        'password' => 'password123',
        'displayName' => 'Test Name',
        'active' => 100,
        'createdDate' => '2024-01-01 12:00:00',
        'updatedDate' => '2024-01-01 12:00:00',
    ],
    'admins/admin_delete_add.sql' => [
        'adminId' => 1,
        'requestDate' => '2024-01-01 12:00:00',
        'scheduleDate' => '2024-01-01 12:00:00',
        'createdDate' => '2024-01-01 12:00:00',
    ],
    'admins/admin_delete_delete.sql' => [
        'deletedDate' => '2024-01-01 12:00:00',
        'adminId' => 1,
    ],
    'admins/admin_delete_list.sql' => [],
    'admins/admin_email_add.sql' => [
        'adminId' => 1,
        'emailAddress' => 'test@example.com',
        'createdDate' => '2024-01-01 12:00:00',
        'updatedDate' => '2024-01-01 12:00:00',
    ],
    'admins/admin_email_by_admin_id.sql' => [
        'adminId' => 1,
    ],
    'admins/admin_email_delete.sql' => [
        'id' => 1,
    ],
    'admins/admin_email_verified.sql' => [
        'verifiedDate' => '2024-01-01 12:00:00',
        'updatedDate' => '2024-01-01 12:00:00',
        'id' => 1,
    ],
    'admins/admin_item.sql' => [
        'id' => 1,
    ],
    'admins/admin_item_by_email.sql' => [
        'emailAddress' => 'test@example.com',
    ],
    'admins/admin_item_by_username.sql' => [
        'username' => 'Test Name',
    ],
    'admins/admin_password_update.sql' => [
        'password' => 'password123',
        'id' => 1,
    ],
    'admins/admin_permission_add.sql' => [
        'adminId' => 1,
        'access' => 'allow',
        'resourceName' => 'Test Name',
        'permissionName' => 'Test Name',
        'createdDate' => '2024-01-01 12:00:00',
    ],
    'admins/admin_permission_by_admin_id.sql' => [
        'adminId' => 1,
    ],
    'admins/admin_token_add.sql' => [
        'adminId' => 1,
        'token' => 'test-token-12345',
        'expireDate' => '2024-01-01 12:00:00',
        'createdDate' => '2024-01-01 12:00:00',
    ],
    'admins/admin_token_item_by_token.sql' => [
        'token' => 'test-token-12345',
    ],
    'admins/admin_token_remove_by_admin_id.sql' => [
        'adminId' => 1,
    ],
    'admins/admin_update.sql' => [
        'username' => 'Test Name',
        'displayName' => 'Test Name',
        'active' => 100,
        'updatedDate' => '2024-01-01 12:00:00',
        'id' => 1,
    ],
    'bad_passwords/bad_password_add.sql' => [
        'password' => 'password123',
        'createdDate' => '2024-01-01 12:00:00',
    ],
    'bad_passwords/bad_password_item.sql' => [
        'password' => 'password123',
    ],
    'email_queue_recipients/email_queue_recipient_add.sql' => [
        'emailQueueId' => 1,
        'recipientType' => '127.0.0.1',
        'recipientEmailAddress' => 'test@example.com',
        'recipientName' => 'Test Name',
        'createdDate' => '2024-01-01 12:00:00',
    ],
    'email_queue_recipients/email_queue_recipient_delete_sent_older.sql' => [
        'sentDate' => '2024-01-01 12:00:00',
    ],
    'email_queue_recipients/email_queue_recipient_list_by_email_queue_ids.sql' => [
        'emailQueueIds' => 'test@example.com',
    ],
    'email_queues/email_queue_add.sql' => [
        'senderEmailAddress' => 'test@example.com',
        'senderName' => 'Test Name',
        'subject' => 'test value',
        'text' => 'test value',
        'html' => 'test value',
        'active' => 100,
        'attempts' => 10,
        'maxAttempts' => 10,
        'scheduleDate' => '2024-01-01 12:00:00',
        'createdDate' => '2024-01-01 12:00:00',
    ],
    'email_queues/email_queue_delete_by_sent_older.sql' => [
        'sentDate' => '2024-01-01 12:00:00',
    ],
    'email_queues/email_queue_list_by_sendable.sql' => [
        'scheduleDate' => '2024-01-01 12:00:00',
    ],
    'email_queues/email_queue_update_active.sql' => [
        'active' => 100,
        'id' => 1,
    ],
    'email_queues/email_queue_update_attempts.sql' => [
        'attempts' => 10,
        'id' => 1,
    ],
    'email_queues/email_queue_update_sent.sql' => [
        'sentDate' => '2024-01-01 12:00:00',
        'attempts' => 10,
        'id' => 1,
    ],
    'throttles/throttle_add.sql' => [
        'throttleKey' => 'test-key',
        'remoteIp' => '127.0.0.1',
        'iterationCount' => 100,
        'maxAttempts' => 10,
        'interval' => '30 minutes',
        'expireDate' => '2024-01-01 12:00:00',
        'createdDate' => '2024-01-01 12:00:00',
        'updatedDate' => '2024-01-01 12:00:00',
    ],
    'throttles/throttle_item_by_key.sql' => [
        'throttleKey' => 'test-key',
    ],
    'throttles/throttle_remove_by_key.sql' => [
        'throttleKey' => 'test-key',
    ],
    'throttles/throttle_update.sql' => [
        'remoteIp' => '127.0.0.1',
        'iterationCount' => 100,
        'expireDate' => '2024-01-01 12:00:00',
        'updatedDate' => '2024-01-01 12:00:00',
        'id' => 1,
    ],
    'users/user_add.sql' => [
        'uid' => '550e8400-e29b-41d4-a716-446655440000',
        'username' => 'Test Name',
        'password' => 'password123',
        'active' => 100,
        'signupDate' => '2024-01-01 12:00:00',
        'leavedDate' => '2024-01-01 12:00:00',
        'purgeDate' => '2024-01-01 12:00:00',
        'lastLoggedInDate' => '2024-01-01 12:00:00',
        'createdDate' => '2024-01-01 12:00:00',
        'updatedDate' => '2024-01-01 12:00:00',
    ],
    'users/user_delete.sql' => [
        'id' => 1,
    ],
    'users/user_item.sql' => [
        'id' => 1,
    ],
    'users/user_item_by_username.sql' => [
        'username' => 'Test Name',
    ],
    'users/user_list_by_expired.sql' => [
        'dateTime' => 'test value',
    ],
    'users/user_list_by_ids.sql' => [
        'ids' => 'test value',
    ],
    'users/user_update.sql' => [
        'username' => 'Test Name',
        'password' => 'password123',
        'active' => 100,
        'signupDate' => '2024-01-01 12:00:00',
        'leavedDate' => '2024-01-01 12:00:00',
        'purgeDate' => '2024-01-01 12:00:00',
        'lastLoggedInDate' => '2024-01-01 12:00:00',
        'updatedDate' => '2024-01-01 12:00:00',
        'id' => 1,
    ],
    'verification_codes/verification_code_add.sql' => [
        'uuid' => '550e8400-e29b-41d4-a716-446655440000',
        'emailAddress' => 'test@example.com',
        'url' => 'https://example.com',
        'code' => 'CODE123',
        'expireDate' => '2024-01-01 12:00:00',
        'createdDate' => '2024-01-01 12:00:00',
        'updatedDate' => '2024-01-01 12:00:00',
    ],
    'verification_codes/verification_code_by_uuid.sql' => [
        'uuid' => '550e8400-e29b-41d4-a716-446655440000',
    ],
    'verification_codes/verification_code_verified.sql' => [
        'verifiedDate' => '2024-01-01 12:00:00',
        'id' => 1,
    ],
];
