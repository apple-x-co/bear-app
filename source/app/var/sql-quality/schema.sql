SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS admin_deletes;
CREATE TABLE `admin_deletes`
    (
        `id` INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
        `admin_id` INTEGER UNSIGNED NOT NULL COMMENT '管理者アカウントID (外部キー設定しない)',
        `request_at` DATETIME NOT NULL COMMENT '申請日時',
        `schedule_at` DATETIME NOT NULL COMMENT '予定日時',
        `deleted_at` DATETIME NULL COMMENT '実行済日時',
        `created_at` DATETIME NOT NULL COMMENT '作成日時',
        PRIMARY KEY (`id`)
    )
ENGINE = InnoDB COMMENT '管理者退会申請';

CREATE UNIQUE INDEX idx_admin_deletes_1 ON admin_deletes (admin_id);
CREATE INDEX idx_admin_deletes_2 ON admin_deletes (schedule_at, deleted_at);

DROP TABLE IF EXISTS admin_emails;
CREATE TABLE `admin_emails`
    (
        `id` INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
        `admin_id` INTEGER UNSIGNED NOT NULL COMMENT '管理者アカウントID',
        `email_address` VARCHAR(100) NOT NULL COMMENT 'Eメールアドレス',
        `verified_at` DATETIME NULL COMMENT '検証済み日時',
        `created_at` DATETIME NOT NULL COMMENT '作成日時',
        `updated_at` DATETIME NOT NULL COMMENT '更新日時',
        PRIMARY KEY (`id`),
        CONSTRAINT `fk_admin_emails_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`)
    )
ENGINE = InnoDB COMMENT '管理者Eメール';

CREATE UNIQUE INDEX idx_admin_emails_1 ON admin_emails (email_address);

DROP TABLE IF EXISTS admin_permissions;
CREATE TABLE `admin_permissions`
    (
        `id` INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
        `admin_id` INTEGER UNSIGNED NOT NULL COMMENT '管理者アカウントID',
        `access` VARCHAR(5) NOT NULL COMMENT 'アクセス',
        `resource_name` VARCHAR(30) NOT NULL COMMENT 'リソース名',
        `permission_name` VARCHAR(10) NOT NULL COMMENT 'パーミッション名',
        `created_at` DATETIME NOT NULL COMMENT '作成日時',
        PRIMARY KEY (`id`),
        CONSTRAINT `fk_admin_permissions_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`)
    )
ENGINE = InnoDB COMMENT '管理者パーミッション';

DROP TABLE IF EXISTS admin_tokens;
CREATE TABLE `admin_tokens`
    (
        `id` INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
        `admin_id` INTEGER UNSIGNED NOT NULL COMMENT '管理者アカウントID',
        `token` VARCHAR(100) NOT NULL COMMENT 'ログイントークン',
        `expire_at` DATETIME NOT NULL COMMENT '有効期限日時',
        `created_at` DATETIME NOT NULL COMMENT '作成日時',
        PRIMARY KEY (`id`),
        CONSTRAINT `fk_admin_tokens_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`)
    )
ENGINE = InnoDB COMMENT '管理者永続ログイントークン';

CREATE UNIQUE INDEX idx_admin_tokens_1 ON admin_tokens (token);

DROP TABLE IF EXISTS admins;
CREATE TABLE `admins`
    (
        `id` INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
        `username` VARCHAR(100) NOT NULL COMMENT 'ユーザー名',
        `password` VARCHAR(255) NOT NULL COMMENT 'パスワード',
        `display_name` VARCHAR(100) NOT NULL COMMENT '表示名',
        `active` SMALLINT UNSIGNED NOT NULL COMMENT 'アクティブ',
        `created_at` DATETIME NOT NULL COMMENT '作成日時',
        `updated_at` DATETIME NOT NULL COMMENT '更新日時',
        PRIMARY KEY (`id`)
    )
ENGINE = InnoDB COMMENT '管理者アカウント';

CREATE UNIQUE INDEX idx_admins_1 ON admins (username);

DROP TABLE IF EXISTS bad_passwords;
CREATE TABLE `bad_passwords`
    (
        `password` VARCHAR(255) NOT NULL COMMENT 'パスワード',
        `created_at` DATETIME NOT NULL COMMENT '作成日時',
        PRIMARY KEY (`password`)
    )
ENGINE = InnoDB COMMENT '悪いパスワード';

DROP TABLE IF EXISTS email_recipients;
CREATE TABLE `email_recipients`
    (
        `id` INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
        `email_id` INTEGER UNSIGNED NOT NULL COMMENT 'EメールID',
        `recipient_type` VARCHAR(10) NOT NULL COMMENT '"to" or "cc" or "bcc" or "replay-to"',
        `recipient_email_address` VARCHAR(100) NOT NULL COMMENT '受信者Eメールアドレス',
        `recipient_name` VARCHAR(100) NULL COMMENT '受信者名',
        `created_at` DATETIME NOT NULL COMMENT '作成日時',
        PRIMARY KEY (`id`),
        CONSTRAINT `fk_email_recipients_1` FOREIGN KEY (`email_id`) REFERENCES `emails` (`id`)
    )
ENGINE = InnoDB COMMENT 'Eメール受信者';

DROP TABLE IF EXISTS emails;
CREATE TABLE `emails`
    (
        `id` INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
        `sender_email_address` VARCHAR(100) NOT NULL COMMENT '送信者Eメールアドレス',
        `sender_name` VARCHAR(100) NULL COMMENT '送信者名',
        `subject` VARCHAR(100) NOT NULL COMMENT '件名',
        `text` TEXT NOT NULL COMMENT 'テキスト',
        `html` TEXT NULL COMMENT 'HTML',
        `schedule_at` DATETIME NOT NULL COMMENT '予定送信日時',
        `sent_at` DATETIME NULL COMMENT '送信日時日時',
        `created_at` DATETIME NOT NULL COMMENT '作成日時',
        PRIMARY KEY (`id`)
    )
ENGINE = InnoDB COMMENT 'Eメール';

DROP TABLE IF EXISTS test;
CREATE TABLE `test` (
  `id` varchar(255) COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

DROP TABLE IF EXISTS throttles;
CREATE TABLE `throttles`
    (
        `id` INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
        `throttle_key` VARCHAR(100) NOT NULL COMMENT 'キー',
        `remote_ip` VARCHAR(100) NOT NULL COMMENT 'リモートIPアドレス',
        `iteration_count` INTEGER UNSIGNED NOT NULL COMMENT '試行回数',
        `max_attempts` INTEGER UNSIGNED NOT NULL COMMENT '最大回数',
        `interval` VARCHAR(20) NOT NULL COMMENT '間隔',
        `expire_at` DATETIME NOT NULL COMMENT '有効期限日時',
        `created_at` DATETIME NOT NULL COMMENT '作成日時',
        `updated_at` DATETIME NOT NULL COMMENT '更新日時',
        PRIMARY KEY (`id`)
    )
ENGINE = InnoDB COMMENT 'スロットリング';

CREATE INDEX `idx_throttles_1` ON `throttles` (`throttle_key`);

DROP TABLE IF EXISTS users;
CREATE TABLE `users`
    (
        `id` INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
        `uid` VARCHAR(36) NOT NULL COMMENT 'ユニークID',
        `username` VARCHAR(100) NOT NULL COMMENT 'ユーザー名',
        `password` VARCHAR(255) NOT NULL COMMENT 'パスワード',
        `active` SMALLINT UNSIGNED NOT NULL COMMENT 'アクティブ',
        `created_at` DATETIME NOT NULL COMMENT '作成日時',
        `updated_at` DATETIME NOT NULL COMMENT '更新日時',
        PRIMARY KEY (`id`)
    )
ENGINE = InnoDB COMMENT 'ユーザーアカウント';

DROP TABLE IF EXISTS verification_codes;
CREATE TABLE `verification_codes`
    (
        `id` INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
        `uuid` VARCHAR(100) NOT NULL COMMENT 'UUID',
        `email_address` VARCHAR(100) NOT NULL COMMENT 'Eメールアドレス',
        `url` TEXT NOT NULL COMMENT 'URL',
        `code` VARCHAR(255) NOT NULL COMMENT 'コード',
        `expire_at` DATETIME NOT NULL COMMENT '有効期限日時',
        `verified_at` DATETIME NULL COMMENT '検証済み日時',
        `created_at` DATETIME NOT NULL COMMENT '作成日時',
        `updated_at` DATETIME NOT NULL COMMENT '更新日時',
        PRIMARY KEY (`id`)
    )
ENGINE = InnoDB COMMENT 'コード認証';

CREATE UNIQUE INDEX idx_codes_1 ON verification_codes (uuid);

-- 外部キーチェックの再有効化
SET FOREIGN_KEY_CHECKS = 1;

-- テストデータ生成用のストアドプロシージャの作成
DELIMITER //

CREATE PROCEDURE GenerateTestData()
BEGIN
    DECLARE i INT DEFAULT 1;

    -- Insert 1000 admins
    WHILE i <= 1000 DO
        INSERT INTO `admins` (`username`, `password`, `display_name`, `active`, `created_at`, `updated_at`)
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
            INSERT INTO `admin_deletes` (`admin_id`, `request_at`, `schedule_at`, `deleted_at`, `created_at`)
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
        INSERT INTO `admin_emails` (`admin_id`, `email_address`, `verified_at`, `created_at`, `updated_at`)
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
        INSERT INTO `admin_permissions` (`admin_id`, `access`, `resource_name`, `permission_name`, `created_at`)
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
        INSERT INTO `admin_tokens` (`admin_id`, `token`, `expire_at`, `created_at`)
        VALUES (i,
                CONCAT('token', i),
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY,
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY);
        SET i = i + 1;
    END WHILE;

    -- Insert 1000 bad_passwords
    SET i = 1;
    WHILE i <= 1000 DO
        INSERT INTO `bad_passwords` (`password`, `created_at`)
        VALUES (CONCAT('password-', i),
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY);
        SET i = i + 1;
    END WHILE;

    -- Insert 1000 emails
    SET i = 1;
    WHILE i <= 1000 DO
        INSERT INTO `emails` (`sender_email_address`, `sender_name`, `subject`, `text`, `html`, `schedule_at`, `sent_at`, `created_at`)
        VALUES ('admin@example.com',
                'Admin',
                'HELLO WORLD',
                'HELLO WORLD',
                '<p>HELLO WORLD</p>',
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY,
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY,
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY);
        SET i = i + 1;
    END WHILE;

    -- Insert 1000 email_recipients
    SET i = 1;
    WHILE i <= 1000 DO
        INSERT INTO `email_recipients` (`email_id`, `recipient_type`, `recipient_email_address`, `recipient_name`, `created_at`)
        VALUES (i,
                'to',
                CONCAT('user', i, '@example.com'),
                CONCAT('user', i),
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY);
        SET i = i + 1;
    END WHILE;

    -- Insert 1000 test
    SET i = 1;
    WHILE i <= 1000 DO
        INSERT INTO `test` (`id`, `title`, `date_created`)
        VALUES (CONCAT('id', i),
                CONCAT('user', i),
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY);
        SET i = i + 1;
    END WHILE;

    -- Insert throttles
    SET i = 1;
    WHILE i <= 1000 DO
        INSERT INTO `throttles` (`throttle_key`, `remote_ip`, `iteration_count`, `max_attempts`, `interval`, `expire_at`, `created_at`, `updated_at`)
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
        INSERT INTO `users` (`uid`, `username`, `password`, `active`, `created_at`, `updated_at`)
        VALUES (CONCAT('uuid-', i),
                CONCAT('User ', i),
                'password',
                IF(i % 10 = 0, 0, 1),
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY,
                NOW() - INTERVAL FLOOR(RAND() * 365) DAY);
        SET i = i + 1;
    END WHILE;

    -- Insert 1000 verification_codes
    SET i = 1;
    WHILE i <= 1000 DO
        INSERT INTO `verification_codes` (`uuid`, `email_address`, `url`, `code`, `expire_at`, `verified_at`, `created_at`, `updated_at`)
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
