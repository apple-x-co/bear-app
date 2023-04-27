CREATE TABLE `admin_tokens`
    (
        `id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
        `admin_id` INTEGER NOT NULL,
        `token` TEXT NOT NULL,
        `expire_at` TEXT NOT NULL,
        `created_at` TEXT NOT NULL,
        FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`)
    )
STRICT;
