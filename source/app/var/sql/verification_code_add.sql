/* verification_code_add */
INSERT INTO `verification_codes` (`uuid`, `email_address`, `url`, `code`, `expire_at`, `verified_at`, `created_at`, `updated_at`)
VALUES (:uuid, :emailAddress, :url, :code, :expireAt, NULL, :createdAt, :updatedAt);

SELECT `uuid` FROM `verification_codes` WHERE `id` = last_insert_id();
