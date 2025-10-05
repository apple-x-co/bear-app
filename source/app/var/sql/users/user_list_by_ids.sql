/** user_list_by_ids */
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
WHERE `id` IN (:ids);
