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
