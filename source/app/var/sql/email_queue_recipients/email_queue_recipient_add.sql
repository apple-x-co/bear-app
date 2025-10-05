/* email_queue_recipient_add */
INSERT INTO `email_queue_recipients` (`email_queue_id`, `recipient_type`, `recipient_email_address`, `recipient_name`,
                                      `created_date`)
VALUES (:emailQueueId, :recipientType, :recipientEmailAddress, :recipientName, :createdDate);
