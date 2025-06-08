<?php

namespace App\TodoList\Application\UseCase\GetTodo;

use App\TodoList\Application\DTO\TodoDTO;
use App\Shared\Domain\ValueObject\Uuid;
use App\TodoList\Domain\Service\TodoServiceInterface;

final class GetTodoHandler
{
  public function __construct(
    private TodoServiceInterface $todoService
  ) {}

  public function __invoke(GetTodoQuery $query): TodoDTO
  {
    $todo = $this->todoService->getTodo(new Uuid($query->todoId));

    return TodoDTO::fromDomain($todo);
  }
}
