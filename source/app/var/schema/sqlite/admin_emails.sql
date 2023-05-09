CREATE TABLE `admin_emails`
    (
        `id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
        `admin_id` INTEGER NOT NULL,
        `email_address` TEXT NOT NULL,
        `verified_at` TEXT NULL,
        `expire_at` TEXT NOT NULL,
        `created_at` TEXT NOT NULL,
        FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`)
    )
STRICT;
