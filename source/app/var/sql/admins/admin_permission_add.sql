/* admin_permission_add */
INSERT INTO `admin_permissions` (`admin_id`, `access`, `resource_name`, `permission_name`, `created_date`)
VALUES (:adminId, :access, :resourceName, :permissionName, :createdDate);
