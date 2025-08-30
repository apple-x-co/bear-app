/* admin_delete_list */
SELECT `admin_id`, `request_date`, `schedule_date`, `deleted_date`, `created_date`
  FROM `admin_deletes`
 WHERE `deleted_date` IS NULL
   AND `schedule_date` <= NOW();
