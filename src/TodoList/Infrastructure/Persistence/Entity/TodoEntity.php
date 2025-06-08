<?php

namespace App\TodoList\Infrastructure\Persistence\Entity;

use App\TodoList\Domain\Entity\Todo;
use App\Shared\Domain\ValueObject\Uuid;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'todos')]
class TodoEntity
{
  #[ORM\Id]
  #[ORM\Column(type: 'string', length: 36)]
  private string $id;

  #[ORM\Column(type: 'string', length: 255)]
  private string $title;

  #[ORM\Column(type: 'text')]
  private string $description;

  #[ORM\Column(type: 'boolean')]
  private bool $completed;

  #[ORM\Column(type: 'datetime_immutable')]
  private \DateTimeImmutable $createdAt;

  #[ORM\Column(type: 'datetime_immutable', nullable: true)]
  private ?\DateTimeImmutable $completedAt;

  public static function fromDomain(Todo $todo): self
  {
    $entity = new self();
    $entity->id = $todo->getId()->value();
    $entity->title = $todo->getTitle();
    $entity->description = $todo->getDescription();
    $entity->completed = $todo->isCompleted();
    $entity->createdAt = $todo->getCreatedAt();
    $entity->completedAt = $todo->getCompletedAt();

    return $entity;
  }

  public function updateFromDomain(Todo $todo): void
  {
    // Nie zmieniamy ID i createdAt - tylko aktualizowalne pola
    $this->title = $todo->getTitle();
    $this->description = $todo->getDescription();
    $this->completed = $todo->isCompleted();
    $this->completedAt = $todo->getCompletedAt();
  }

  public function toDomain(): Todo
  {
    return new Todo(
      new Uuid($this->id),
      $this->title,
      $this->description,
      $this->completed,
      $this->createdAt,
      $this->completedAt
    );
  }
}
