CREATE TABLE `bad_passwords`
    (
        `password` VARCHAR(255) NOT NULL COMMENT 'パスワード',
        `created_at` DATETIME NOT NULL COMMENT '作成日時',
        PRIMARY KEY (`password`)
    )
ENGINE = InnoDB COMMENT '悪いパスワード';
