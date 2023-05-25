/* admin_item_username */
SELECT `id`, `username`, `password`, `display_name`, `active`, `created_at`, `updated_at`
  FROM `admins`
 WHERE `username` = :username;
