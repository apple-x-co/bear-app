/* verification_code_add */
INSERT INTO `verification_codes` (`uuid`, `email_address`, `url`, `code`, `expire_date`, `verified_date`, `created_date`, `updated_date`)
VALUES (:uuid, :emailAddress, :url, :code, :expireDate, NULL, :createdDate, :updatedDate);

SELECT `uuid` FROM `verification_codes` WHERE `id` = last_insert_id();
