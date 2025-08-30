SELECT `id`, `uuid`, `email_address`, `url`, `code`, `expire_date`, `verified_date`, `created_date`, `updated_date`
  FROM `verification_codes`
 WHERE `uuid` = :uuid
   AND `expire_date` >= NOW()
   AND `verified_date` IS NULL;
