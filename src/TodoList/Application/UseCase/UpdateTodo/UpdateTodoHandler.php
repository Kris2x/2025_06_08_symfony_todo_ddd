<?php

namespace App\TodoList\Application\UseCase\UpdateTodo;

use App\TodoList\Application\DTO\TodoDTO;
use App\Shared\Domain\ValueObject\Uuid;
use App\TodoList\Domain\Service\TodoServiceInterface;

final class UpdateTodoHandler
{
  public function __construct(
    private TodoServiceInterface $todoService
  ) {}

  public function __invoke(UpdateTodoCommand $command): TodoDTO
  {
    $todo = $this->todoService->updateTodo(
      new Uuid($command->todoId),
      $command->title,
      $command->description
    );

    return TodoDTO::fromDomain($todo);
  }
}
