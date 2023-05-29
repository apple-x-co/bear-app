/* bad_password_add */
INSERT INTO `bad_passwords` (`password`, `created_at`)
VALUES (:password, :createdAt);
