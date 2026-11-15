CREATE TABLE `user_remembers`
(
    `selector`         VARCHAR(32)  NOT NULL COMMENT 'セレクター（Cookie の公開部分）',
    `hashed_validator` VARCHAR(64)  NOT NULL COMMENT 'バリデーターの SHA-256 ハッシュ',
    `username`         VARCHAR(255) NOT NULL COMMENT 'ユーザー名',
    `userdata`         TEXT         NULL COMMENT 'ユーザーデータ（JSON）',
    `expires`          INT          NOT NULL COMMENT '有効期限（UNIX タイムスタンプ）',
    PRIMARY KEY (`selector`),
    CONSTRAINT `fk_user_remembers_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci COMMENT 'ユーザーの Remember me トークン（Aura.Auth の PdoRememberStorage）';

CREATE INDEX `idx_user_remembers_1` ON `user_remembers` (`username`);
