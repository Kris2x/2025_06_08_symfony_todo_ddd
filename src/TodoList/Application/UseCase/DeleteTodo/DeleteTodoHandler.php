<?php

namespace App\TodoList\Application\UseCase\DeleteTodo;

use App\Shared\Domain\ValueObject\Uuid;
use App\TodoList\Domain\Service\TodoServiceInterface;

final class DeleteTodoHandler
{
  public function __construct(
    private TodoServiceInterface $todoService
  ) {}

  public function __invoke(DeleteTodoCommand $command): void
  {
    $this->todoService->deleteTodo(new Uuid($command->todoId));
  }
}
