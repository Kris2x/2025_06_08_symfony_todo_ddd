<?php

namespace App\TodoList\Application\UseCase\CreateTodo;

use App\TodoList\Application\DTO\TodoDTO;
use App\TodoList\Domain\Service\TodoServiceInterface;

final class CreateTodoHandler
{
  public function __construct(
    private TodoServiceInterface $todoService
  ) {}

  public function __invoke(CreateTodoCommand $command): TodoDTO
  {
    $todo = $this->todoService->createTodo(
      $command->title,
      $command->description
    );

    return TodoDTO::fromDomain($todo);
  }
}
