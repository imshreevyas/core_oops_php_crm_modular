<?php
namespace CoreCRM\Interfaces;

interface RepositoryInterface {
    public function all(): array;
    public function find(int $id): ?object;
    public function save(object $entity): object;
    public function delete(int $id): bool;
}
