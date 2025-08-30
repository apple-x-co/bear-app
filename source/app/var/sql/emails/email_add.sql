/* email_add */
INSERT INTO `emails` (`sender_email_address`, `sender_name`, `subject`, `text`, `html`, `schedule_date`, `sent_date`, `created_date`)
VALUES (:senderEmailAddress, :senderName, :subject, :text, :html, :scheduleDate, NULL, :createdDate);

SELECT last_insert_id() AS id;
