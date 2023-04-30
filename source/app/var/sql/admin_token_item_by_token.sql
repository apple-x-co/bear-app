/* admin_token_item_by_token */
SELECT `id`, `admin_id`, `token`, `expire_at`, `created_at`
  FROM `admin_tokens`
 WHERE `token` = :token;
