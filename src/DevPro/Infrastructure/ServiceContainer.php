<?php
declare(strict_types=1);

namespace DevPro\Infrastructure;

use DevPro\Application\ApplicationInterface;

interface ServiceContainer
{
    public function application(): ApplicationInterface;
}
