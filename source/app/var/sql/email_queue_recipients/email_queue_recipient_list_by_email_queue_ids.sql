/* email_queue_recipient_list */
SELECT `id`, `email_queue_id`, `recipient_type`, `recipient_email_address`, `recipient_name`, `created_date`
FROM `email_queue_recipients`
WHERE `email_queue_id` IN (:emailQueueIds)
ORDER BY `id` DESC;
