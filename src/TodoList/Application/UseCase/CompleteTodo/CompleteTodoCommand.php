<?php

namespace App\TodoList\Application\UseCase\CompleteTodo;

final readonly class CompleteTodoCommand
{
  public function __construct(
    public string $todoId
  ) {}
}
