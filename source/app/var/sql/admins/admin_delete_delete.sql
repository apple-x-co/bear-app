/* admin_delete_delete */
UPDATE `admin_deletes`
   SET `deleted_date` = :deletedDate
 WHERE `admin_id` = :adminId;

DELETE
  FROM `admin_emails`
 WHERE `admin_id` = :adminId;

DELETE
  FROM `admin_permissions`
 WHERE `admin_id` = :adminId;

-- 同じユーザー名で作り直したアカウントに、古い Remember me の Cookie で入れないように消す
DELETE
  FROM `admin_remembers`
 WHERE `username` = (SELECT `username` FROM `admins` WHERE `id` = :adminId);

DELETE
  FROM `admins`
 WHERE `id` = :adminId;
