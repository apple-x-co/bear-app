/* admin_update */
UPDATE `admins`
   SET `username` = :username,
       `display_name` = :displayName,
       `active` = :active,
       `updated_at` = :updatedAt
 WHERE `id` = :id;
