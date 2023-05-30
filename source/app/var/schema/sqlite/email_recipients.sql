CREATE TABLE `email_recipients`
    (
        `id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
        `email_id` INTEGER NOT NULL,
        `recipient_type` TEXT NOT NULL,
        `recipient_email_address` TEXT NOT NULL,
        `recipient_name` TEXT NULL,
        `created_at` TEXT NOT NULL,
        FOREIGN KEY (`email_id`) REFERENCES `emails` (`id`)
    )
STRICT;
