<?php

namespace App\TodoList\Domain\Service;

use App\TodoList\Domain\Entity\Todo;
use App\Shared\Domain\ValueObject\Uuid;

interface TodoServiceInterface
{
  public function createTodo(string $title, string $description): Todo;
  public function getTodo(Uuid $id): Todo;
  public function updateTodo(Uuid $id, string $title, string $description): Todo;
  public function completeTodo(Uuid $id): Todo;
  public function deleteTodo(Uuid $id): void;
  public function getAllTodos(): array;
}
