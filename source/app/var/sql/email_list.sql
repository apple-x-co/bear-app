/* email_list */
SELECT `id`, `sender_email_address`, `sender_name`, `subject`, `text`, `html`, `schedule_at`, `sent_at`, `created_at`
  FROM `emails`
 WHERE `schedule_at` <= NOW()
   AND `sent_at` IS NULL;
