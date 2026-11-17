CREATE TABLE `admin_deletes`
(
    `id`            INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `admin_id`      INTEGER UNSIGNED                NOT NULL COMMENT '管理者アカウントID (外部キー設定しない)',
    `request_date`  DATETIME                        NOT NULL COMMENT '申請日時',
    `schedule_date` DATETIME                        NOT NULL COMMENT '予定日時',
    `deleted_date`  DATETIME                        NULL COMMENT '実行済日時',
    `created_date`  DATETIME                        NOT NULL COMMENT '作成日時',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci COMMENT '管理者退会申請';

CREATE UNIQUE INDEX `idx_admin_deletes_1` ON `admin_deletes` (`admin_id`);
CREATE INDEX `idx_admin_deletes_2` ON `admin_deletes` (`deleted_date`);
