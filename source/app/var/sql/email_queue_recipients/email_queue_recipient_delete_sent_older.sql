/* email_queue_recipient_delete_sent_older */
DELETE
FROM `email_queue_recipients`
WHERE EXISTS(SELECT 1
             FROM `email_queues`
             WHERE `email_queues`.`id` = `email_queue_recipients`.`email_queue_id`
               AND `email_queues`.`sent_date` <= :sentDate);
