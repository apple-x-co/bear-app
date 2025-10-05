CREATE TABLE `users`
(
    `id`                  INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
    `uid`                 VARCHAR(36)                     NOT NULL COMMENT 'ユニークID',
    `username`            VARCHAR(255)                    NOT NULL COMMENT 'ユーザー名',
    `password`            VARCHAR(255)                    NOT NULL COMMENT 'パスワード',
    `active`              SMALLINT UNSIGNED               NOT NULL COMMENT 'アクティブ',
    `signup_date`         DATETIME                        NOT NULL COMMENT '登録日時',
    `leaved_date`         DATETIME                        NULL COMMENT '退会日時',
    `purge_date`          DATETIME                        NULL COMMENT '削除日時',
    `last_logged_in_date` DATETIME                        NULL COMMENT '最終ログイン日時',
    `created_date`        DATETIME                        NOT NULL COMMENT '作成日時',
    `updated_date`        DATETIME                        NOT NULL COMMENT '更新日時',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci COMMENT 'ユーザーアカウント';

CREATE UNIQUE INDEX `idx_users_1` ON `users` (`username`);
CREATE INDEX `idx_users_2` ON `users` (`purge_date`);
