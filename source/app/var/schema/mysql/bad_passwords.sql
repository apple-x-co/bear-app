CREATE TABLE `bad_passwords`
(
    `password`     VARCHAR(255) NOT NULL COMMENT 'パスワード',
    `created_date` DATETIME     NOT NULL COMMENT '作成日時',
    PRIMARY KEY (`password`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci COMMENT '悪いパスワード';
