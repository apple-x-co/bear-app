/* admin_add */
INSERT INTO `admins` (`username`, `password`, `display_name`, `active`, `created_date`, `updated_date`)
VALUES (:username, :password, :displayName, :active, :createdDate, :updatedDate);

SELECT last_insert_id() AS id;
