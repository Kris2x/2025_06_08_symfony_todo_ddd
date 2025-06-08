<?php

namespace App\TodoList\Application\DTO;

use App\TodoList\Domain\Entity\Todo;

final readonly class TodoDTO
{
  public function __construct(
    public string $id,
    public string $title,
    public string $description,
    public bool $completed,
    public string $createdAt,
    public ?string $completedAt
  ) {}

  public static function fromDomain(Todo $todo): self
  {
    return new self(
      $todo->getId()->value(),
      $todo->getTitle(),
      $todo->getDescription(),
      $todo->isCompleted(),
      $todo->getCreatedAt()->format('Y-m-d H:i:s'),
      $todo->getCompletedAt()?->format('Y-m-d H:i:s')
    );
  }
}
