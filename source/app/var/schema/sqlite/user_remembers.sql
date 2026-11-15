CREATE TABLE `user_remembers`
    (
        `selector` TEXT PRIMARY KEY NOT NULL,
        `hashed_validator` TEXT NOT NULL,
        `username` TEXT NOT NULL,
        `userdata` TEXT NULL,
        `expires` INTEGER NOT NULL
    )
STRICT;

CREATE INDEX `idx_user_remembers_1` ON `user_remembers` (`username`);
