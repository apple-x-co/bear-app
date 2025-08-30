/* admin_token_item_by_token */
SELECT `id`, `admin_id`, `token`, `expire_date`, `created_date`
  FROM `admin_tokens`
 WHERE `token` = :token;
