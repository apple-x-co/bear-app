/* admin_item */
SELECT `id`, `username`, `password`, `display_name`, `active`, `created_at`, `updated_at`
  FROM `admins`
 WHERE `id` = :id;
