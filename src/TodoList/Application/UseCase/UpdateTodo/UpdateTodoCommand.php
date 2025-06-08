<?php

namespace App\TodoList\Application\UseCase\UpdateTodo;

final readonly class UpdateTodoCommand
{
  public function __construct(
    public string $todoId,
    public string $title,
    public string $description
  ) {}
}
