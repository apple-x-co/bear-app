/** user_item */
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
WHERE `id` = :id;
