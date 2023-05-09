/* admin_password_update */
UPDATE `admins`
   SET `password` = :password,
       `updated_at` = now()
 WHERE `id` = :id;
