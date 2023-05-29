/* admin_delete_list */
SELECT `admin_id`, `request_at`, `schedule_at`, `deleted_at`, `created_at`
  FROM `admin_deletes`
 WHERE `schedule_at` <= NOW()
   AND `deleted_at` IS NULL;
