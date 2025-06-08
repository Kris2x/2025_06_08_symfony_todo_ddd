<?php

namespace App\Tests\Unit\Application\UseCase;

use App\TodoList\Application\UseCase\CreateTodo\CreateTodoCommand;
use App\TodoList\Application\UseCase\CreateTodo\CreateTodoHandler;
use App\TodoList\Domain\Service\TodoService;
use App\TodoList\Domain\Entity\Todo;
use App\Shared\Domain\ValueObject\Uuid;
use App\TodoList\Domain\Service\TodoServiceInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

final class CreateTodoHandlerTest extends TestCase
{
  private TodoService|MockObject $todoService;
  private CreateTodoHandler $handler;

  protected function setUp(): void
  {
    $this->todoService = $this->createMock(TodoServiceInterface::class);
    $this->handler = new CreateTodoHandler($this->todoService);
  }

  public function test_should_create_todo_successfully(): void
  {
    $command = new CreateTodoCommand('Test Title', 'Test Description');
    $expectedTodo = new Todo(Uuid::random(), 'Test Title', 'Test Description');

    $this->todoService
      ->expects($this->once())
      ->method('createTodo')
      ->with('Test Title', 'Test Description')
      ->willReturn($expectedTodo);

    $result = $this->handler->__invoke($command); // ← Zmienione na __invoke

    $this->assertEquals($expectedTodo->getTitle(), $result->title);
    $this->assertEquals($expectedTodo->getDescription(), $result->description);
  }
}
