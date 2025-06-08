<?php

namespace App\TodoList\Domain\Entity;

use App\Shared\Domain\ValueObject\Uuid;

final class Todo
{
  private Uuid $id;
  private string $title;
  private string $description;
  private bool $completed;
  private \DateTimeImmutable $createdAt;
  private ?\DateTimeImmutable $completedAt;

  public function __construct(
    Uuid $id,
    string $title,
    string $description,
    bool $completed = false,
    ?\DateTimeImmutable $createdAt = null,
    ?\DateTimeImmutable $completedAt = null
  ) {
    $this->id = $id;
    $this->title = $title;
    $this->description = $description;
    $this->completed = $completed;
    $this->createdAt = $createdAt ?? new \DateTimeImmutable();
    $this->completedAt = $completedAt;
  }

  public function getId(): Uuid
  {
    return $this->id;
  }

  public function getTitle(): string
  {
    return $this->title;
  }

  public function getDescription(): string
  {
    return $this->description;
  }

  public function isCompleted(): bool
  {
    return $this->completed;
  }

  public function getCreatedAt(): \DateTimeImmutable
  {
    return $this->createdAt;
  }

  public function getCompletedAt(): ?\DateTimeImmutable
  {
    return $this->completedAt;
  }

  public function markAsCompleted(): void
  {
    $this->completed = true;
    $this->completedAt = new \DateTimeImmutable();
  }

  public function markAsIncomplete(): void
  {
    $this->completed = false;
    $this->completedAt = null;
  }

  public function updateTitle(string $title): void
  {
    $this->title = $title;
  }

  public function updateDescription(string $description): void
  {
    $this->description = $description;
  }
}
