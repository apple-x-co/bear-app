CREATE TABLE `admin_permissions`
    (
        `id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
        `admin_id` INTEGER NOT NULL,
        `access` TEXT NOT NULL,
        `resource_name` TEXT NOT NULL,
        `permission_name` TEXT NOT NULL,
        `created_at` TEXT NOT NULL,
        FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`)
    )
STRICT;
