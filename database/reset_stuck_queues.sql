-- Reset stuck queues from previous days
-- This will change all "Waiting" queues from days before today to "Finished"

UPDATE queues 
SET status = 'Finished', 
    updated_at = NOW() 
WHERE status = 'Waiting' 
AND DATE(created_at) < CURDATE();

-- Verify the update
SELECT COUNT(*) as updated_count 
FROM queues 
WHERE status = 'Finished' 
AND DATE(updated_at) = CURDATE()
AND DATE(created_at) < CURDATE();
