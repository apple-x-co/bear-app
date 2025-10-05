/* email_queue_add */
INSERT INTO `email_queues` (`sender_email_address`, `sender_name`, `subject`, `text`, `html`, `active`, `attempts`,
                            `max_attempts`, `schedule_date`, `sent_date`, `created_date`)
VALUES (:senderEmailAddress, :senderName, :subject, :text, :html, :active, :attempts, :maxAttempts, :scheduleDate, NULL,
        :createdDate);

SELECT last_insert_id() AS id;
