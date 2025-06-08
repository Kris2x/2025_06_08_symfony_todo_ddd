<?php

namespace App\TodoList\Domain\Service;

use App\TodoList\Domain\Entity\Todo;
use App\TodoList\Domain\Repository\TodoRepositoryInterface;
use App\TodoList\Domain\Exception\TodoNotFoundException;
use App\Shared\Domain\ValueObject\Uuid;

final class TodoService implements TodoServiceInterface
{
  public function __construct(
    private TodoRepositoryInterface $todoRepository
  ) {}

  public function createTodo(string $title, string $description): Todo
  {
    $todo = new Todo(
      Uuid::random(),
      $title,
      $description
    );

    $this->todoRepository->save($todo);

    return $todo;
  }

  public function getTodo(Uuid $id): Todo
  {
    $todo = $this->todoRepository->findById($id);

    if (!$todo) {
      throw new TodoNotFoundException("Todo with ID {$id->value()} not found");
    }

    return $todo;
  }

  public function updateTodo(Uuid $id, string $title, string $description): Todo
  {
    $todo = $this->getTodo($id);
    $todo->updateTitle($title);
    $todo->updateDescription($description);

    $this->todoRepository->save($todo);

    return $todo;
  }

  public function completeTodo(Uuid $id): Todo
  {
    $todo = $this->getTodo($id);
    $todo->markAsCompleted();

    $this->todoRepository->save($todo);

    return $todo;
  }

  public function deleteTodo(Uuid $id): void
  {
    $this->getTodo($id); // Sprawdź czy exists
    $this->todoRepository->delete($id);
  }

  public function getAllTodos(): array
  {
    return $this->todoRepository->findAll();
  }
}
