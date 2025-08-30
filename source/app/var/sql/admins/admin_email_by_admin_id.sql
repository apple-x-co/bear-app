/* admin_email_by_admin_id */
SELECT `id`, `admin_id`, `email_address`, `verified_date`, `created_date`, `updated_date`
  FROM `admin_emails`
 WHERE `admin_id` = :adminId;
