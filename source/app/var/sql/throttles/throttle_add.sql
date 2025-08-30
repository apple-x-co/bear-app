/* throttle_add */
INSERT INTO `throttles` (`throttle_key`, `remote_ip`, `iteration_count`, `max_datetempts`, `interval`, `expire_date`, `created_date`, `updated_date`)
VALUES (:throttleKey, :remoteIp, :iterationCount, :maxDatetempts, :interval, :expireDate, :createdDate, :updatedDate);
