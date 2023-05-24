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
