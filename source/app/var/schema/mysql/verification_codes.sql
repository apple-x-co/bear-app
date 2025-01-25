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

CREATE UNIQUE INDEX `idx_codes_1` ON `verification_codes` (`uuid`);
