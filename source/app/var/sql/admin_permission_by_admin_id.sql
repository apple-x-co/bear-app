/* admin_permission_by_admin_id */
SELECT `id`, `admin_id`, `access`, `resource_name`, `permission_name`, `created_at`
  FROM `admin_permissions`
 WHERE `admin_id` = :adminId;
