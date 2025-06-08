<?php

namespace App\TodoList\Application\UseCase\CompleteTodo;

use App\TodoList\Application\DTO\TodoDTO;
use App\Shared\Domain\ValueObject\Uuid;
use App\TodoList\Domain\Service\TodoServiceInterface;

final class CompleteTodoHandler
{
  public function __construct(
    private TodoServiceInterface $todoService
  ) {}

  public function __invoke(CompleteTodoCommand $command): TodoDTO
  {
    $todo = $this->todoService->completeTodo(new Uuid($command->todoId));

    return TodoDTO::fromDomain($todo);
  }
}
