CREATE TABLE `email_queues`
(
    `id`                   BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `sender_email_address` VARCHAR(100)                   NOT NULL COMMENT '送信者Eメールアドレス',
    `sender_name`          VARCHAR(100)                   NULL COMMENT '送信者名',
    `subject`              VARCHAR(100)                   NOT NULL COMMENT '件名',
    `text`                 TEXT                           NOT NULL COMMENT 'テキスト',
    `html`                 TEXT                           NULL COMMENT 'HTML',
    `active`               SMALLINT UNSIGNED              NOT NULL COMMENT 'アクティブ',
    `attempts`             SMALLINT UNSIGNED              NOT NULL COMMENT '試行回数',
    `max_attempts`         SMALLINT UNSIGNED              NOT NULL COMMENT '最大試行回数',
    `schedule_date`        DATETIME                       NOT NULL COMMENT '予定送信日時',
    `sent_date`            DATETIME                       NULL COMMENT '送信日時日時',
    `created_date`         DATETIME                       NOT NULL COMMENT '作成日時',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci COMMENT 'Eメールキュー';

CREATE INDEX `idx_email_queues_1` ON `email_queues` (`sent_date`);
