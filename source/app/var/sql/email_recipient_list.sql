/* email_recipient_list */
SELECT `id`, `email_id`, `recipient_type`, `recipient_email_address`, `recipient_name`, `created_at`
  FROM `email_recipients`
 WHERE `email_id` IN (:emailIds);
