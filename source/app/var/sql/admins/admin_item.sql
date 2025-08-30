/* admin_item */
SELECT `id`, `username`, `password`, `display_name`, `active`, `created_date`, `updated_date`
  FROM `admins`
 WHERE `id` = :id;
