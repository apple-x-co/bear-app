/* email_sent */
UPDATE `emails`
   SET `sent_at` = :sentAt
 WHERE `id` = :id;
