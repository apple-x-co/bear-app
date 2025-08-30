/* throttle_item_by_key */
SELECT `id`, `throttle_key`, `remote_ip`, `iteration_count`, `max_attempts`, `interval`, `expire_date`, `created_date`, `updated_date`
  FROM `throttles`
 WHERE `throttle_key` = :throttleKey
   AND `expire_date` >= NOW();
