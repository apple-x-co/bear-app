CREATE TABLE `email_queue_recipients`
(
    `id`                      BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `email_queue_id`          BIGINT UNSIGNED                NOT NULL COMMENT 'EメールID',
    `recipient_type`          VARCHAR(10)                    NOT NULL COMMENT 'to/cc/bcc/replay-to',
    `recipient_email_address` VARCHAR(100)                   NOT NULL COMMENT '受信者Eメールアドレス',
    `recipient_name`          VARCHAR(100)                   NULL COMMENT '受信者名',
    `created_date`            DATETIME                       NOT NULL COMMENT '作成日時',
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_email_queue_recipients_1` FOREIGN KEY (`email_queue_id`) REFERENCES `email_queues` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci COMMENT 'Eメールキュー受信者';
