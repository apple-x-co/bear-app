/* admin_delete_add */
INSERT INTO `admin_deletes` (`admin_id`, `request_date`, `schedule_date`, `deleted_date`, `created_date`)
VALUES (:adminId, :requestDate, :scheduleDate, NULL, :createdDate);
