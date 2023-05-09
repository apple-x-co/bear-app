/* admin_email_by_admin_id */
SELECT `id`, `admin_id`, `email_address`, `verified_at`, `created_at`, `updated_at`
  FROM `admin_emails`
 WHERE `admin_id` = :adminId;
