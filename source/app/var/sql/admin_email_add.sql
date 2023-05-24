/* admin_email_add */
INSERT INTO `admin_emails` (`admin_id`, `email_address`, `verified_at`, `created_at`, `updated_at`)
VALUES (:adminId, :emailAddress, NULL, :createdAt, :updatedAt);

SELECT last_insert_id() AS id;
