CREATE TABLE `email_recipients`
(
    `id`                      BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
    `email_id`                BIGINT UNSIGNED NOT NULL COMMENT 'EメールID',
    `recipient_type`          VARCHAR(10)  NOT NULL COMMENT '"to" or "cc" or "bcc" or "replay-to"',
    `recipient_email_address` VARCHAR(100) NOT NULL COMMENT '受信者Eメールアドレス',
    `recipient_name`          VARCHAR(100) NULL COMMENT '受信者名',
    `created_date`            DATETIME     NOT NULL COMMENT '作成日時',
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_email_recipients_1` FOREIGN KEY (`email_id`) REFERENCES `emails` (`id`)
) ENGINE = InnoDB COMMENT 'Eメール受信者';
