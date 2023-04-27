/* admin_item */
SELECT `id`, `username`, `password`, `active`, `created_at`, `updated_at`
  FROM admins
 WHERE `id` = :id
