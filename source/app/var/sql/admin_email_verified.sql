/* admin_email_update */
UPDATE `admin_emails`
   SET `verified_at` = :verifiedAt,
       `updated_at` = :updatedAt
 WHERE `id` = :id;
