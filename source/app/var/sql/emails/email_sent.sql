/* email_sent */
UPDATE `emails`
   SET `sent_date` = :sentDate
 WHERE `id` = :id;
