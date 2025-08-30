/* verification_code_verified */
UPDATE `verification_codes`
   SET `verified_date` = :verifiedDate,
       `updated_date` = NOW()
 WHERE `id` = :id;
