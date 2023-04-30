CREATE TABLE `throttles`
    (
        `id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
        `throttle_key` TEXT NOT NULL,
        `remote_ip` TEXT NOT NULL,
        `iteration_count` INTEGER NOT NULL,
        `max_attempts` INTEGER NOT NULL,
        `interval` TEXT NOT NULL,
        `expire_at` TEXT NOT NULL,
        `created_at` TEXT NOT NULL,
        `updated_at` TEXT NOT NULL
    )
STRICT;
