/** user_delete */
-- 同じユーザー名で作り直したアカウントに、古い Remember me の Cookie で入れないように消す
DELETE
FROM `user_remembers`
WHERE `username` = (SELECT `username` FROM `users` WHERE `id` = :id);

DELETE
FROM `users`
WHERE `id` = :id;
