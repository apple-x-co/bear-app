CREATE TABLE `emails`
(
    `id`                   BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `sender_email_address` VARCHAR(100) NOT NULL COMMENT '送信者Eメールアドレス',
    `sender_name`          VARCHAR(100) NULL COMMENT '送信者名',
    `subject`              VARCHAR(100) NOT NULL COMMENT '件名',
    `text`                 TEXT         NOT NULL COMMENT 'テキスト',
    `html`                 TEXT NULL COMMENT 'HTML',
    `schedule_date`        DATETIME     NOT NULL COMMENT '予定送信日時',
    `sent_date`            DATETIME NULL COMMENT '送信日時日時',
    `created_date`         DATETIME     NOT NULL COMMENT '作成日時',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB COMMENT 'Eメール';

CREATE INDEX `idx_emails_1` ON `emails` (`sent_date`);
