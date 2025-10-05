/** user_add */
INSERT INTO `users` (`uid`, `username`, `password`, `active`, `signup_date`, `leaved_date`, `purge_date`,
                     `last_logged_in_date`, `created_date`, `updated_date`)
VALUES (:uid, :username, :password, :active, :signupDate, :leavedDate, :purgeDate, :lastLoggedInDate, :createdDate,
        :updatedDate);

SELECT last_insert_id() AS id;
