<?php

namespace App\TodoList\Application\UseCase\GetTodo;

final readonly class GetTodoQuery
{
  public function __construct(
    public string $todoId
  ) {}
}
