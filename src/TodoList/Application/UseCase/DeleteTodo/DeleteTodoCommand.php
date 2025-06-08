<?php

namespace App\TodoList\Application\UseCase\DeleteTodo;

final readonly class DeleteTodoCommand
{
  public function __construct(
    public string $todoId
  ) {}
}
