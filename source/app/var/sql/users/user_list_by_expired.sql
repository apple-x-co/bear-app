/** user_list_by_expired */
SELECT `id`
     , `uid`
     , `username`
     , `password`
     , `active`
     , `signup_date`
     , `leaved_date`
     , `purge_date`
     , `last_logged_in_date`
     , `created_date`
     , `updated_date`
FROM `users`
WHERE `active` = 0
  AND `leaved_date` < :dateTime
  AND `purge_date` < :dateTime;
