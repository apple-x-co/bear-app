/* email_queue_update_active */
UPDATE `email_queues`
SET `active` = :active
WHERE `id` = :id;
