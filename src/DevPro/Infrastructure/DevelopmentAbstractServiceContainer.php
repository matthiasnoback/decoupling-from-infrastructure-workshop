<?php
declare(strict_types=1);

namespace DevPro\Infrastructure;

use Common\EventDispatcher\EventDispatcher;
use DevPro\Application\Clock;

final class DevelopmentAbstractServiceContainer extends AbstractServiceContainer
{
    protected function clock(): Clock
    {
        return new SystemClock();
    }

    protected function registerSubscribers(EventDispatcher $eventDispatcher): void
    {
        // Register subscribers that should be available in every environment in the parent method
        parent::registerSubscribers($eventDispatcher);

        // Register additional event subscribers that are only meant to be notified in a development environment:
    }
}
