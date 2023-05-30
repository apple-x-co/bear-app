CREATE TABLE `emails`
    (
        `id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
        `sender_email_address` TEXT NOT NULL,
        `sender_name` TEXT NULL,
        `subject` TEXT NOT NULL,
        `text` TEXT NOT NULL,
        `html` TEXT NULL,
        `schedule_at` TEXT NOT NULL,
        `sent_at` TEXT NULL,
        `created_at` TEXT NOT NULL
    )
STRICT;
