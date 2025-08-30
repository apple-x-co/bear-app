/* throttle_remove_by_key */
DELETE FROM `throttles` WHERE `throttle_key` = :throttleKey;
