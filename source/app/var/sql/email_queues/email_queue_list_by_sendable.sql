/* email_queue_list_by_sendable */
SELECT `id`
     , `sender_email_address`
     , `sender_name`
     , `subject`
     , `text`
     , `html`
     , `active`
     , `attempts`
     , `max_attempts`
     , `schedule_date`
     , `sent_date`
     , `created_date`
FROM `email_queues`
WHERE `sent_date` IS NULL
  AND `schedule_date` <= :scheduleDate
  AND `active` = 1
  AND `max_attempts` > `attempts`
ORDER BY `id` DESC;
