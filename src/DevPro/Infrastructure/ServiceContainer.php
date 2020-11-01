<?php
declare(strict_types=1);

namespace DevPro\Infrastructure;

interface ServiceContainer
{
    public function boot(): void;
}
