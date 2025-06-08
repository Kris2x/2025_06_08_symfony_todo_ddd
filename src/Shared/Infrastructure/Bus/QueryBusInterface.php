<?php

namespace App\Shared\Infrastructure\Bus;

interface QueryBusInterface
{
  public function __invoke(object $query): mixed;
}
