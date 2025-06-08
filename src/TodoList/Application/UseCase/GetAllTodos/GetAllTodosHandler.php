<?php

namespace App\TodoList\Application\UseCase\GetAllTodos;

use App\TodoList\Application\DTO\TodoDTO;
use App\TodoList\Domain\Service\TodoServiceInterface;

final class GetAllTodosHandler
{
  public function __construct(
    private TodoServiceInterface $todoService
  ) {}

  public function __invoke(GetAllTodosQuery $query): array
  {
    $todos = $this->todoService->getAllTodos();

    return array_map(
      fn($todo) => TodoDTO::fromDomain($todo),
      $todos
    );
  }
}
