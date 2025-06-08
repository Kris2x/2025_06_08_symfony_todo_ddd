<?php

namespace App\TodoList\Application\UseCase\CreateTodo;

final readonly class CreateTodoCommand
{
  public function __construct(
    public string $title,
    public string $description
  ) {}
}
