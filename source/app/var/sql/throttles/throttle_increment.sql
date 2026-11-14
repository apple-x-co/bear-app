/* throttle_increment */
-- throttle_key のユニーク制約で、同時に呼ばれても 1 行を行ロックで順に更新する
-- iteration_count は expire_date を更新する前に評価させるため、先頭に書く
INSERT INTO `throttles` (`throttle_key`, `remote_ip`, `iteration_count`, `max_attempts`, `interval`, `expire_date`, `created_date`, `updated_date`)
VALUES (:throttleKey, :remoteIp, 1, :maxAttempts, :interval, :expireDate, :createdDate, :updatedDate) AS `new`
    ON DUPLICATE KEY UPDATE
       `iteration_count` = IF(`throttles`.`expire_date` < NOW(), 1, `throttles`.`iteration_count` + 1),
       `remote_ip` = `new`.`remote_ip`,
       `expire_date` = `new`.`expire_date`,
       `updated_date` = `new`.`updated_date`;
