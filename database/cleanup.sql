-- Hapus data queue dengan status selain Waiting dan Finished
DELETE FROM queues WHERE status NOT IN ('Waiting', 'Finished');
