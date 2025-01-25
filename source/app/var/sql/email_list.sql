/* email_list */
SELECT `id`, `sender_email_address`, `sender_name`, `subject`, `text`, `html`, `schedule_at`, `sent_at`, `created_at`
  FROM `emails`
 WHERE `sent_at` IS NULL
   AND `schedule_at` <= NOW();
