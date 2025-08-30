/* admin_item_by_email */
SELECT `id`, `username`, `password`, `display_name`, `active`, `created_date`, `updated_date`
  FROM `admins`
 WHERE EXISTS(SELECT 1
                FROM `admin_emails`
               WHERE `admin_emails`.`admin_id` = `admins`.`id`
                 AND `admin_emails`.`email_address` = :emailAddress);
