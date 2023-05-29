/* admin_delete_delete */
UPDATE `admin_deletes`
   SET `deleted_at` = :deletedAt
 WHERE `admin_id` = :adminId;

DELETE
  FROM `admin_emails`
 WHERE `admin_id` = :adminId;

DELETE
  FROM `admin_tokens`
 WHERE `admin_id` = :adminId;

DELETE
  FROM `admins`
 WHERE `id` = :adminId;
