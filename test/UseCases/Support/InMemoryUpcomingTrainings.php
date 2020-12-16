<?php
declare(strict_types=1);

namespace Test\UseCases\Support;

use DevPro\Application\Training\UpcomingTrainings;

final class InMemoryUpcomingTrainings implements UpcomingTrainings
{
    public function findAll(): array
    {
        return [];
    }
}
