/* verification_code_verified */
UPDATE `verification_codes`
   SET `verified_at` = :verifiedAt,
       `updated_at` = NOW()
 WHERE `id` = :id;
