/* email_queues_update_attempts */
UPDATE `email_queues`
SET `attempts` = :attempts
WHERE `id` = :id;
