/* admin_delete_add */
INSERT INTO `admin_deletes` (`admin_id`, `request_at`, `schedule_at`, `deleted_at`, `created_at`)
VALUES (:adminId, :requestAt, :scheduleAt, NULL, :createdAt);
