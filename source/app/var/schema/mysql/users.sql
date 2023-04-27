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
