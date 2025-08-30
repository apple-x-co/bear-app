/* admin_tokens_add */
INSERT INTO `admin_tokens` (`admin_id`, `token`, `expire_date`, `created_date`)
VALUES (:adminId, :token, :expireDate, :createdDate);
