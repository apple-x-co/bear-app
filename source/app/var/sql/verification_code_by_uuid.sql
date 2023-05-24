SELECT `id`, `uuid`, `email_address`, `url`, `code`, `expire_at`, `verified_at`, `created_at`, `updated_at`
  FROM `verification_codes`
 WHERE `uuid` = :uuid
   AND `expire_at` >= NOW()
   AND `verified_at` IS NULL;
