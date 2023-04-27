/* admin_tokens_add */
INSERT INTO `admin_tokens` (`admin_id`, `token`, `expire_at`, `created_at`)
VALUES (:adminId, :token, :expireAt, :createdAt);
