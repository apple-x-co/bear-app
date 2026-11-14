/* throttle_item_by_key_including_expired */
-- UPSERT 直後の読み直し用。そのあいだに期限が切れても行を取れるよう、期限では絞り込まない
SELECT `id`, `throttle_key`, `remote_ip`, `iteration_count`, `max_attempts`, `interval`, `expire_date`, `created_date`, `updated_date`
  FROM `throttles`
 WHERE `throttle_key` = :throttleKey;
