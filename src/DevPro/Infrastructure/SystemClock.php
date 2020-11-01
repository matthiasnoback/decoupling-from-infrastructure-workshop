<?php
declare(strict_types=1);

namespace DevPro\Infrastructure;

use DateTimeImmutable;
use DevPro\Application\Clock;

final class SystemClock implements Clock
{
    public function currentTime(): DateTimeImmutable
    {
        return new DateTimeImmutable('now');
    }
}
