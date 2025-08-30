/* throttle_update */
UPDATE `throttles`
   SET `remote_ip` = :remoteIp,
       `iteration_count` = :iterationCount,
       `expire_date` = :expireDate,
       `updated_date` = :updatedDate
 WHERE `id` = :id;
