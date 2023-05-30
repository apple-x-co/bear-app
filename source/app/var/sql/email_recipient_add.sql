/* email_recipient_add */
INSERT INTO `email_recipients` (`email_id`, `recipient_type`, `recipient_email_address`, `recipient_name`, `created_at`)
VALUES (:emailId, :recipientType, :recipientEmailAddress, :recipientName, :createdAt);
