<?php

namespace App\Shared\Domain\ValueObject;

final readonly class Uuid
{
  public function __construct(
    private string $value
  ) {
    if (!$this->isValid($value)) {
      throw new \InvalidArgumentException("Invalid UUID format: {$value}");
    }
  }

  public static function random(): self
  {
    return new self(\Ramsey\Uuid\Uuid::uuid4()->toString());
  }

  public function value(): string
  {
    return $this->value;
  }

  private function isValid(string $uuid): bool
  {
    return \Ramsey\Uuid\Uuid::isValid($uuid);
  }

  public function equals(Uuid $other): bool
  {
    return $this->value === $other->value;
  }
}
