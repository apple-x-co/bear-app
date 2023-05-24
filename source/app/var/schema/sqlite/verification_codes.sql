CREATE TABLE `verification_codes`
    (
        `id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
        `uuid` TEXT NOT NULL,
        `email_address` TEXT NOT NULL,
        `url` TEXT NOT NULL,
        `code` TEXT NOT NULL,
        `expire_at` TEXT NOT NULL,
        `verified_at` TEXT NULL,
        `created_at` TEXT NOT NULL,
        `updated_at` TEXT NOT NULL
    )
STRICT;
