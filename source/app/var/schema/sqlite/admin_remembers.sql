CREATE TABLE `admin_remembers`
    (
        `selector` TEXT PRIMARY KEY NOT NULL,
        `hashed_validator` TEXT NOT NULL,
        `username` TEXT NOT NULL,
        `userdata` TEXT NULL,
        `expires` INTEGER NOT NULL
    )
STRICT;

CREATE INDEX `idx_admin_remembers_1` ON `admin_remembers` (`username`);
