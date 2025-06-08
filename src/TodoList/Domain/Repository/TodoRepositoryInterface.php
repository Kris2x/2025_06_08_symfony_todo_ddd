<?php

namespace App\TodoList\Domain\Repository;

use App\TodoList\Domain\Entity\Todo;
use App\Shared\Domain\ValueObject\Uuid;

interface TodoRepositoryInterface
{
  public function save(Todo $todo): void;
  public function findById(Uuid $id): ?Todo;
  public function findAll(): array;
  public function delete(Uuid $id): void;
}
