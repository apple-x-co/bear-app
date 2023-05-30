/* email_add */
INSERT INTO `emails` (`sender_email_address`, `sender_name`, `subject`, `text`, `html`, `schedule_at`, `sent_at`, `created_at`)
VALUES (:senderEmailAddress, :senderName, :subject, :text, :html, :scheduleAt, NULL, :createdAt);

SELECT last_insert_id() AS id;
