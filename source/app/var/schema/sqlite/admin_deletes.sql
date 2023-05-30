CREATE TABLE `admin_deletes`
    (
        `id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
        `admin_id` INTEGER NOT NULL,
        `request_at` TEXT NOT NULL,
        `schedule_at` TEXT NOT NULL,
        `deleted_at` TEXT NULL,
        `created_at` TEXT NOT NULL
    )
STRICT;
