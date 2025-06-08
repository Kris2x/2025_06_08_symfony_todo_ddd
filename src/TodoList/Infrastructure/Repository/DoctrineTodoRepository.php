<?php

namespace App\TodoList\Infrastructure\Repository;

use App\TodoList\Domain\Entity\Todo;
use App\TodoList\Domain\Repository\TodoRepositoryInterface;
use App\TodoList\Infrastructure\Persistence\Entity\TodoEntity;
use App\Shared\Domain\ValueObject\Uuid;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineTodoRepository implements TodoRepositoryInterface
{
  public function __construct(
    private EntityManagerInterface $entityManager
  ) {}

  public function save(Todo $todo): void
  {
    // Sprawdź czy encja już istnieje w bazie
    $existingEntity = $this->entityManager
      ->getRepository(TodoEntity::class)
      ->find($todo->getId()->value());

    if ($existingEntity) {
      // UPDATE - użyj wygodnej metody updateFromDomain
      $existingEntity->updateFromDomain($todo);
      // EntityManager automatycznie wykryje zmiany (dirty checking)
    } else {
      // CREATE - dodaj nową encję
      $todoEntity = TodoEntity::fromDomain($todo);
      $this->entityManager->persist($todoEntity);
    }

    $this->entityManager->flush();
  }

  public function findById(Uuid $id): ?Todo
  {
    $todoEntity = $this->entityManager
      ->getRepository(TodoEntity::class)
      ->find($id->value());

    return $todoEntity?->toDomain();
  }

  public function findAll(): array
  {
    $todoEntities = $this->entityManager
      ->getRepository(TodoEntity::class)
      ->findAll();

    return array_map(
      fn(TodoEntity $entity) => $entity->toDomain(),
      $todoEntities
    );
  }

  public function delete(Uuid $id): void
  {
    $todoEntity = $this->entityManager
      ->getRepository(TodoEntity::class)
      ->find($id->value());

    if ($todoEntity) {
      $this->entityManager->remove($todoEntity);
      $this->entityManager->flush();
    }
  }
}
