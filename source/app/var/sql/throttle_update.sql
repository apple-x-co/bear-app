/* throttle_update */
UPDATE `throttles`
   SET `remote_ip` = :remoteIp,
       `iteration_count` = :iterationCount,
       `expire_at` = :expireAt,
       `updated_at` = :updatedAt
 WHERE `id` = :id;
