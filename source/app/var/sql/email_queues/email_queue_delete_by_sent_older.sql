/* email_queue_delete_by_sent_older */
DELETE
FROM `email_queues`
WHERE `sent_date` <= :sentDate;
