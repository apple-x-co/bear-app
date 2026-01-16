-- TablePlus の Import ではなく手動で実行すること!

-- テストデータ生成用のストアドプロシージャの作成
DELIMITER //

CREATE PROCEDURE GenerateTestData()
BEGIN
    DECLARE i INT DEFAULT 1;

    -- Insert 1000 admins
    WHILE i <= 1000 DO
        INSERT INTO `admins` (`username`, `password`, `display_name`, `active`, `created_date`, `updated_date`)
        VALUES (CONCAT('User ', i),
                'password',
                CONCAT('user', i),
                IF(i % 10 = 0, 0, 1),
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY,
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY);
        SET i = i + 1;
    END WHILE;

    -- Insert 1000 admin_deletes
    SET i = 1;
    WHILE i <= 1000 DO
        IF i % 10 = 0 THEN
            INSERT INTO `admin_deletes` (`admin_id`, `request_date`, `schedule_date`, `deleted_date`, `created_date`)
            VALUES (i,
                    NOW() - INTERVAL FLOOR(RAND() * 365) DAY,
                    NOW() - INTERVAL FLOOR(RAND() * 365) DAY,
                    NOW() - INTERVAL FLOOR(RAND() * 365) DAY,
                    NOW() - INTERVAL FLOOR(RAND() * 365) DAY);
        END IF;
        SET i = i + 1;
    END WHILE;

    -- Insert 1000 admin_emails
    SET i = 1;
    WHILE i <= 1000 DO
        INSERT INTO `admin_emails` (`admin_id`, `email_address`, `verified_date`, `created_date`, `updated_date`)
        VALUES (i,
                CONCAT('user', i, '@example.com'),
                IF(i % 10 = 0, NOW() - INTERVAL FLOOR(RAND() * 365) DAY, NULL),
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY,
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY);
        SET i = i + 1;
    END WHILE;

    -- Insert 1000 admin_permissions
    SET i = 1;
    WHILE i <= 1000 DO
        INSERT INTO `admin_permissions` (`admin_id`, `access`, `resource_name`, `permission_name`, `created_date`)
        VALUES (i,
                'allow',
                'settings',
                'read',
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY);
        SET i = i + 1;
    END WHILE;

    -- Insert 1000 admin_tokens
    SET i = 1;
    WHILE i <= 1000 DO
        INSERT INTO `admin_tokens` (`admin_id`, `token`, `expire_date`, `created_date`)
        VALUES (i,
                CONCAT('token', i),
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY,
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY);
        SET i = i + 1;
    END WHILE;

    -- Insert 1000 bad_passwords
    SET i = 1;
    WHILE i <= 1000 DO
        INSERT INTO `bad_passwords` (`password`, `created_date`)
        VALUES (CONCAT('password-', i),
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY);
        SET i = i + 1;
    END WHILE;

    -- Insert 1000 email_queues
    SET i = 1;
    WHILE i <= 1000 DO
        INSERT INTO `email_queues` (`sender_email_address`, `sender_name`, `subject`, `text`, `html`, `active`, `attempts`, `max_attempts`, `schedule_date`, `sent_date`, `created_date`)
        VALUES ('admin@example.com',
                'Admin',
                'HELLO WORLD',
                'HELLO WORLD',
                '<p>HELLO WORLD</p>',
                1,
                0,
                5,
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY,
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY,
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY);
        SET i = i + 1;
    END WHILE;

    -- Insert 1000 email_queue_recipients
    SET i = 1;
    WHILE i <= 1000 DO
        INSERT INTO `email_queue_recipients` (`email_queue_id`, `recipient_type`, `recipient_email_address`, `recipient_name`, `created_date`)
        VALUES (i,
                'to',
                CONCAT('user', i, '@example.com'),
                CONCAT('user', i),
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY);
        SET i = i + 1;
    END WHILE;

    -- Insert throttles
    SET i = 1;
    WHILE i <= 1000 DO
        INSERT INTO `throttles` (`throttle_key`, `remote_ip`, `iteration_count`, `max_attempts`, `interval`, `expire_date`, `created_date`, `updated_date`)
        VALUES (CONCAT('key', i),
                '127.0.0.1',
                3,
                10,
                '30 minutes',
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY,
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY,
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY);
        SET i = i + 1;
    END WHILE;

    -- Insert 1000 users
    SET i = 1;
    WHILE i <= 1000 DO
        INSERT INTO `users` (`uid`, `display_name`, `username`, `password`, `active`, `signup_date`, `created_date`, `updated_date`)
        VALUES (CONCAT('uuid-', i),
                CONCAT('Name ', i),
                CONCAT('User ', i),
                'password',
                IF(i % 10 = 0, 0, 1),
                NOW(),
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY,
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY);
        SET i = i + 1;
    END WHILE;

    -- Insert 1000 verification_codes
    SET i = 1;
    WHILE i <= 1000 DO
        INSERT INTO `verification_codes` (`uuid`, `email_address`, `url`, `code`, `expire_date`, `verified_date`, `created_date`, `updated_date`)
        VALUES (CONCAT('uuid-', i),
                CONCAT('user', i, '@example.com'),
                'https://example.com',
                CONCAT('code', i),
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY,
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY,
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY,
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY);
        SET i = i + 1;
    END WHILE;
END//

DELIMITER ;

-- テストデータの生成
CALL GenerateTestData();

-- ストアドプロシージャの削除
DROP PROCEDURE GenerateTestData;
