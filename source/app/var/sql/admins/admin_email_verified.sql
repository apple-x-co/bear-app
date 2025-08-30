/* admin_email_update */
UPDATE `admin_emails`
   SET `verified_date` = :verifiedDate,
       `updated_date` = :updatedDate
 WHERE `id` = :id;
