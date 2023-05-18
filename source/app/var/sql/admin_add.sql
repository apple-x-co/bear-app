/* admin_add */
INSERT INTO `admins` (`username`, `password`, `display_name`, `active`, `created_at`, `updated_at`)
VALUES (:username, :password, :displayName, :active, :createdAt, :updatedAt);

SELECT last_insert_id() AS id;
