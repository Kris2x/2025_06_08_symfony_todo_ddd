<?php

namespace App\Shared\Infrastructure\Bus;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class SymfonyQueryBus implements QueryBusInterface
{
  public function __construct(
    private MessageBusInterface $queryBus
  ) {}

  public function __invoke(object $query): mixed
  {
    $envelope = $this->queryBus->dispatch($query);

    /** @var HandledStamp $handledStamp */
    $handledStamp = $envelope->last(HandledStamp::class);

    return $handledStamp?->getResult();
  }
}
