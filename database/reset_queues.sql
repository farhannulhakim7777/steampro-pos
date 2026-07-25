-- Reset Queue Board
-- WARNING: This will delete all queue data
-- Use with caution!

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE queues;
SET FOREIGN_KEY_CHECKS = 1;

-- Reset auto-increment
ALTER TABLE queues AUTO_INCREMENT = 1;
