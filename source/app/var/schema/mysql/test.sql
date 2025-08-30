CREATE TABLE `test`
(
    `id`           varchar(255) COLLATE utf8mb4_bin                       NOT NULL DEFAULT '',
    `title`        varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
    `date_created` datetime                                               NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
