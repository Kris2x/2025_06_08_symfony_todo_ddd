<?php

namespace App\Shared\Infrastructure\Bus;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class SymfonyCommandBus implements CommandBusInterface
{
  public function __construct(
    private MessageBusInterface $commandBus
  ) {}

  public function __invoke(object $command): mixed
  {
    $envelope = $this->commandBus->dispatch($command);

    /** @var HandledStamp $handledStamp */
    $handledStamp = $envelope->last(HandledStamp::class);

    return $handledStamp?->getResult();
  }
}
