<?php
namespace App\Model;

use PDO;
use PDOException;

class Model {
    protected string $dsn = 'pgsql:host=127.0.0.1;port=5432;dbname=gestion_dette_boutique'; // DSN pour PostgreSQL
    protected string $username = 'postgres';
    protected string $password = 'root';
    protected PDO|null $pdo = null;
    protected string $table;

    public function ouvrirConnexion(): void {
        try {
            if ($this->pdo === null) {
                $this->pdo = new PDO($this->dsn, $this->username, $this->password);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Ajoutez cette ligne pour gérer les erreurs
            }
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            // Vous pourriez aussi lancer une exception ici si vous souhaitez gérer cela différemment
        }
    }
    public function testConnection(): bool {
        try {
            $this->ouvrirConnexion();
            return $this->pdo !== null;
        } catch (PDOException $e) {
            echo 'Connection test failed: ' . $e->getMessage();
            return false;
        }
    }
        

    public function fermerConnexion(): void {
        if ($this->pdo !== null) {
            $this->pdo = null;
        }
    }

    protected function executeSelect(string $sql, array $params = [], bool $fetch = false): array|false {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $fetch ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
        return [];
    }

    protected function executeUpdate(string $sql, array $params = []): int {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
        return 0;
    }

    public function findAll(): array {
        return $this->executeSelect("SELECT * FROM $this->table");
    }

    public function findById(int $id): ?array {
        $result = $this->executeSelect("SELECT * FROM $this->table WHERE `id` = :id", ['id' => $id], true);
        return $result ?: null;
    }
    
}
