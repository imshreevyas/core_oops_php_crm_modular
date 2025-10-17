<?php
namespace CoreCRM\Repositories;

use CoreCRM\Interfaces\RepositoryInterface;
use CoreCRM\Models\Customer;
use CoreCRM\Database\Connection;

class MysqlCustomerRepository implements RepositoryInterface {
    private \PDO $pdo;

    public function __construct(Connection $connection) {
        $this->pdo = $connection->pdo();
        $this->ensureTable();
    }

    private function ensureTable(): void {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS customers (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(191) NOT NULL,
                email VARCHAR(191),
                phone VARCHAR(50),
                created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }

    public function all(): array {
        $stmt = $this->pdo->query("SELECT * FROM customers ORDER BY id ASC");
        $rows = $stmt->fetchAll();
        return array_map([$this, 'rowToCustomer'], $rows);
    }

    public function find(int $id): ?object {
        $stmt = $this->pdo->prepare("SELECT * FROM customers WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? $this->rowToCustomer($row) : null;
    }

    public function save(object $entity): object {
        if (!($entity instanceof Customer)) {
            throw new \InvalidArgumentException('Entity must be Customer');
        }

        if ($entity->id() === null) {
            $stmt = $this->pdo->prepare("INSERT INTO customers (name,email,phone,created_at) VALUES (:name,:email,:phone,:created)");
            $stmt->execute([
                'name' => $entity->name(),
                'email' => $entity->email(),
                'phone' => $entity->phone(),
                'created' => $entity->getCreatedAt()?->format('Y-m-d H:i:s') ?? (new \DateTime())->format('Y-m-d H:i:s'),
            ]);
            $id = (int)$this->pdo->lastInsertId();
            $ref = new \ReflectionClass($entity);
            $prop = $ref->getProperty('id');
            $prop->setAccessible(true);
            $prop->setValue($entity, $id);
        } else {
            $stmt = $this->pdo->prepare("UPDATE customers SET name=:name,email=:email,phone=:phone,updated_at=:updated WHERE id=:id");
            $stmt->execute([
                'name' => $entity->name(),
                'email' => $entity->email(),
                'phone' => $entity->phone(),
                'updated' => $entity->getUpdatedAt()?->format('Y-m-d H:i:s') ?? (new \DateTime())->format('Y-m-d H:i:s'),
                'id' => $entity->id(),
            ]);
        }

        return $entity;
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM customers WHERE id=:id");
        return $stmt->execute(['id' => $id]);
    }

    private function rowToCustomer(array $row): Customer {
        $customer = new Customer($row['name'], $row['email'] ?? null, $row['phone'] ?? null, (int)$row['id']);
        // set created/updated via reflection
        $ref = new \ReflectionClass($customer);
        if (isset($row['created_at'])) {
            $prop = $ref->getProperty('createdAt');
            $prop->setAccessible(true);
            $prop->setValue($customer, new \DateTime($row['created_at']));
        }
        if (isset($row['updated_at'])) {
            $prop = $ref->getProperty('updatedAt');
            $prop->setAccessible(true);
            $prop->setValue($customer, new \DateTime($row['updated_at']));
        }
        return $customer;
    }
}
