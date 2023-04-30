/* throttle_item_by_key */
SELECT `id`, `throttle_key`, `remote_ip`, `iteration_count`, `max_attempts`, `interval`, `expire_at`, `created_at`, `updated_at`
  FROM `throttles`
 WHERE `throttle_key` = :throttleKey
   AND `expire_at` >= NOW();
