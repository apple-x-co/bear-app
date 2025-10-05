/** user_update */
UPDATE `users`
SET `username`            = :username
  , `password`            = :password
  , `active`              = :active
  , `signup_date`         = :signupDate
  , `leaved_date`         = :leavedDate
  , `purge_date`          = :purgeDate
  , `last_logged_in_date` = :lastLoggedInDate
  , `updated_date`        = :updatedDate
WHERE `id` = :id;
