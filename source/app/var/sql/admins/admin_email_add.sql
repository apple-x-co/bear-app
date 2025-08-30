/* admin_email_add */
INSERT INTO `admin_emails` (`admin_id`, `email_address`, `verified_date`, `created_date`, `updated_date`)
VALUES (:adminId, :emailAddress, NULL, :createdDate, :updatedDate);

SELECT last_insert_id() AS id;
