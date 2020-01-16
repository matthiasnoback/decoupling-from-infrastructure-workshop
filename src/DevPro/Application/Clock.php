<?php
declare(strict_types=1);

namespace DevPro\Application;

use DateTimeImmutable;

interface Clock
{
    public function currentTime(): DateTimeImmutable;
}
