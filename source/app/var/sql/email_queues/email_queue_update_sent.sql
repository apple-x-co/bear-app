/* email_queue_sent */
UPDATE `email_queues`
SET `sent_date` = :sentDate
  , `attempts`  = :attempts
WHERE `id` = :id;
