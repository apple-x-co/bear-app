SELECT `password`, `created_at`
  FROM `bad_passwords`
 WHERE `password` = :password;
