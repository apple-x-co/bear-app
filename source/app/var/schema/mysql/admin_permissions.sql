CREATE TABLE `admin_permissions`
    (
        `id` INTEGER UNSIGNED AUTO_INCREMENT NOT NULL,
        `admin_id` INTEGER UNSIGNED NOT NULL COMMENT '管理者アカウントID',
        `access` VARCHAR(5) NOT NULL COMMENT 'アクセス',
        `resource_name` VARCHAR(30) NOT NULL COMMENT 'リソース名',
        `permission_name` VARCHAR(10) NOT NULL COMMENT 'パーミッション名',
        `created_at` DATETIME NOT NULL COMMENT '作成日時',
        PRIMARY KEY (`id`),
        CONSTRAINT `fk_admin_permissions_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`)
    )
ENGINE = InnoDB COMMENT '管理者パーミッション';
