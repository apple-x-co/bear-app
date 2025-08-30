CREATE TABLE `admin_tokens`
(
    `id`           INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `admin_id`     INTEGER UNSIGNED NOT NULL COMMENT '管理者アカウントID',
    `token`        VARCHAR(100) NOT NULL COMMENT 'ログイントークン',
    `expire_date`  DATETIME     NOT NULL COMMENT '有効期限日時',
    `created_date` DATETIME     NOT NULL COMMENT '作成日時',
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_admin_tokens_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`)
) ENGINE = InnoDB COMMENT '管理者永続ログイントークン';

CREATE UNIQUE INDEX `idx_admin_tokens_1` ON `admin_tokens` (`token`);
