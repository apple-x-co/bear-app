/* admin_permission_add */
INSERT INTO `admin_permissions` (`admin_id`, `access`, `resource_name`, `permission_name`, `created_at`)
VALUES (:adminId, :access, :resourceName, :permissionName, :createdAt);
