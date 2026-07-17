<?php

namespace App\Core;

abstract class Controller
{
    protected function db(): \PDO
    {
        return Database::connection();
    }

    protected function log(string $action, ?string $entity = null, ?int $entityId = null): void
    {
        $stmt = $this->db()->prepare('INSERT INTO activity_logs (user_id, action, entity, entity_id, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            Auth::id(),
            $action,
            $entity,
            $entityId,
            $_SERVER['REMOTE_ADDR'] ?? null,
            substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
        ]);
    }
}

