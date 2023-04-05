CREATE TABLE `users`
    (
        `id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
        `uid` TEXT NOT NULL,
        `username` TEXT NOT NULL,
        `password` TEXT NOT NULL,
        `active` INTEGER NOT NULL,
        `created_at` TEXT NOT NULL,
        `updated_at` TEXT NOT NULL
    )
STRICT;
