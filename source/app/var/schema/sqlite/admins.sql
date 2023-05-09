CREATE TABLE `admins`
    (
        `id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
        `username` TEXT NOT NULL,
        `password` TEXT NOT NULL,
        `display_name` TEXT NOT NULL,
        `active` INTEGER NOT NULL,
        `created_at` TEXT NOT NULL,
        `updated_at` TEXT NOT NULL
    )
STRICT;
