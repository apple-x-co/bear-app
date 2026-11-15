CREATE TABLE `admin_remembers`
(
    `selector`         VARCHAR(32)  NOT NULL COMMENT 'セレクター（Cookie の公開部分）',
    `hashed_validator` VARCHAR(64)  NOT NULL COMMENT 'バリデーターの SHA-256 ハッシュ',
    `username`         VARCHAR(100) NOT NULL COMMENT 'ユーザー名',
    `userdata`         TEXT         NULL COMMENT 'ユーザーデータ（JSON）',
    `expires`          INT          NOT NULL COMMENT '有効期限（UNIX タイムスタンプ）',
    PRIMARY KEY (`selector`),
    CONSTRAINT `fk_admin_remembers_1` FOREIGN KEY (`username`) REFERENCES `admins` (`username`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE = InnoDB COMMENT '管理者の Remember me トークン（Aura.Auth の PdoRememberStorage）';

CREATE INDEX `idx_admin_remembers_1` ON `admin_remembers` (`username`);
