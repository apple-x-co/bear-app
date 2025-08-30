/* admin_password_update */
UPDATE `admins`
   SET `password` = :password,
       `updated_date` = now()
 WHERE `id` = :id;
