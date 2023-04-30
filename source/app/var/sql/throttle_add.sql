/* throttle_add */
INSERT INTO `throttles` (`throttle_key`, `remote_ip`, `iteration_count`, `max_attempts`, `interval`, `expire_at`, `created_at`, `updated_at`)
VALUES (:throttleKey, :remoteIp, :iterationCount, :maxAttempts, :interval, :expireAt, :createdAt, :updatedAt);
