CREATE TABLE `admin_emails`
(
    `id`            INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `admin_id`      INTEGER UNSIGNED NOT NULL COMMENT '管理者アカウントID',
    `email_address` VARCHAR(100) NOT NULL COMMENT 'Eメールアドレス',
    `verified_date` DATETIME NULL COMMENT '検証済み日時',
    `created_date`  DATETIME     NOT NULL COMMENT '作成日時',
    `updated_date`  DATETIME     NOT NULL COMMENT '更新日時',
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_admin_emails_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`)
) ENGINE = InnoDB COMMENT '管理者Eメール';

CREATE UNIQUE INDEX `idx_admin_emails_1` ON `admin_emails` (`email_address`);
