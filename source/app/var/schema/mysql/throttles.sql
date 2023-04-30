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
