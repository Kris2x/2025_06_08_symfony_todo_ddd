<?php

namespace App\Tests\Unit\Domain\Entity;

use App\TodoList\Domain\Entity\Todo;
use App\Shared\Domain\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;

final class TodoTest extends TestCase
{
  public function test_should_create_todo_with_correct_data(): void
  {
    $id = Uuid::random();
    $title = 'Test Todo';
    $description = 'Test Description';

    $todo = new Todo($id, $title, $description);

    $this->assertEquals($id, $todo->getId());
    $this->assertEquals($title, $todo->getTitle());
    $this->assertEquals($description, $todo->getDescription());
    $this->assertFalse($todo->isCompleted());
    $this->assertNull($todo->getCompletedAt());
  }

  public function test_should_mark_todo_as_completed(): void
  {
    $todo = new Todo(Uuid::random(), 'Test', 'Description');

    $todo->markAsCompleted();

    $this->assertTrue($todo->isCompleted());
    $this->assertNotNull($todo->getCompletedAt());
  }

  public function test_should_update_title_and_description(): void
  {
    $todo = new Todo(Uuid::random(), 'Old Title', 'Old Description');

    $todo->updateTitle('New Title');
    $todo->updateDescription('New Description');

    $this->assertEquals('New Title', $todo->getTitle());
    $this->assertEquals('New Description', $todo->getDescription());
  }
}
