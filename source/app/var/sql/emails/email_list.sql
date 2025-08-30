/* email_list */
SELECT `id`, `sender_email_address`, `sender_name`, `subject`, `text`, `html`, `schedule_date`, `sent_date`, `created_date`
  FROM `emails`
 WHERE `sent_date` IS NULL
   AND `schedule_date` <= NOW();
