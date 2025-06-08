<?php

namespace App\TodoList\Infrastructure\Controller;

use App\TodoList\Application\UseCase\CreateTodo\CreateTodoCommand;
use App\TodoList\Application\UseCase\DeleteTodo\DeleteTodoCommand;
use App\TodoList\Application\UseCase\GetAllTodos\GetAllTodosQuery;
use App\TodoList\Application\UseCase\GetTodo\GetTodoQuery;
use App\TodoList\Application\UseCase\UpdateTodo\UpdateTodoCommand;
use App\TodoList\Application\UseCase\CompleteTodo\CompleteTodoCommand;
use App\TodoList\Domain\Exception\TodoNotFoundException;
use App\Shared\Infrastructure\Bus\CommandBusInterface;
use App\Shared\Infrastructure\Bus\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/todos')]
final class TodoController extends AbstractController
{
  public function __construct(
    private CommandBusInterface $commandBus,
    private QueryBusInterface $queryBus
  ) {}

  #[Route('', methods: ['GET'])]
  public function getAllTodos(): JsonResponse
  {
    $query = new GetAllTodosQuery();
    $todos = ($this->queryBus)($query);

    return $this->json($todos);
  }

  #[Route('/{id}', methods: ['GET'])]
  public function getTodo(string $id): JsonResponse
  {
    try {
      $query = new GetTodoQuery($id);
      $todo = ($this->queryBus)($query);
      return $this->json($todo);
    } catch (TodoNotFoundException $e) {
      return $this->json(['error' => $e->getMessage()], 404);
    }
  }

  #[Route('', methods: ['POST'])]
  public function createTodo(Request $request): JsonResponse
  {
    $data = json_decode($request->getContent(), true);

    $command = new CreateTodoCommand(
      $data['title'] ?? '',
      $data['description'] ?? ''
    );

    $todoDTO = ($this->commandBus)($command);

    return $this->json($todoDTO, 201);
  }

  #[Route('/{id}', methods: ['PUT'])]
  public function updateTodo(string $id, Request $request): JsonResponse
  {
    try {
      $data = json_decode($request->getContent(), true);

      $command = new UpdateTodoCommand(
        $id,
        $data['title'] ?? '',
        $data['description'] ?? ''
      );

      $todoDTO = ($this->commandBus)($command);
      return $this->json($todoDTO);
    } catch (TodoNotFoundException $e) {
      return $this->json(['error' => $e->getMessage()], 404);
    }
  }

  #[Route('/{id}/complete', methods: ['PATCH'])]
  public function completeTodo(string $id): JsonResponse
  {
    try {
      $command = new CompleteTodoCommand($id);
      $todo = ($this->commandBus)($command);
      return $this->json($todo);
    } catch (TodoNotFoundException $e) {
      return $this->json(['error' => $e->getMessage()], 404);
    }
  }

  #[Route('/{id}', methods: ['DELETE'])]
  public function deleteTodo(string $id): JsonResponse
  {
    try {
      $command = new DeleteTodoCommand($id);
      ($this->commandBus)($command);
      return $this->json(null, 204);
    } catch (TodoNotFoundException $e) {
      return $this->json(['error' => $e->getMessage()], 404);
    }
  }
}
