CREATE TABLE `user_remembers`
    (
        `selector` TEXT PRIMARY KEY NOT NULL,
        `hashed_validator` TEXT NOT NULL,
        `username` TEXT NOT NULL,
        `userdata` TEXT NULL,
        `expires` INTEGER NOT NULL,
        FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON UPDATE CASCADE ON DELETE CASCADE
    )
STRICT;

CREATE INDEX `idx_user_remembers_1` ON `user_remembers` (`username`);
