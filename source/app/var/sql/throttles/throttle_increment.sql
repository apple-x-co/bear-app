/* throttle_increment */
-- throttle_key のユニーク制約で、同時に呼ばれても 1 行を行ロックで順に更新する
-- 期限内は回数だけ増やし、期限・上限は変えない（拒否されたアクセスで期限が延び続けないように）
-- 期限切れなら新しい期間として、回数・上限・間隔・期限をリセットする
-- `throttles`.`expire_date` は更新前の値を参照したいので、`expire_date` の代入は最後に書く
INSERT INTO `throttles` (`throttle_key`, `remote_ip`, `iteration_count`, `max_attempts`, `interval`, `expire_date`, `created_date`, `updated_date`)
VALUES (:throttleKey, :remoteIp, 1, :maxAttempts, :interval, :expireDate, :createdDate, :updatedDate) AS `new`
    ON DUPLICATE KEY UPDATE
       `iteration_count` = IF(`throttles`.`expire_date` < NOW(), 1, `throttles`.`iteration_count` + 1),
       `max_attempts` = IF(`throttles`.`expire_date` < NOW(), `new`.`max_attempts`, `throttles`.`max_attempts`),
       `interval` = IF(`throttles`.`expire_date` < NOW(), `new`.`interval`, `throttles`.`interval`),
       `remote_ip` = `new`.`remote_ip`,
       `updated_date` = `new`.`updated_date`,
       `expire_date` = IF(`throttles`.`expire_date` < NOW(), `new`.`expire_date`, `throttles`.`expire_date`);
