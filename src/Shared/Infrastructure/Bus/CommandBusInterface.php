<?php

namespace App\Shared\Infrastructure\Bus;

interface CommandBusInterface
{
  public function __invoke(object $command): mixed;
}
