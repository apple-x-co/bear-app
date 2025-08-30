SELECT `password`, `created_date`
  FROM `bad_passwords`
 WHERE `password` = :password;
