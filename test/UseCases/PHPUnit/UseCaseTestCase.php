<?php
declare(strict_types=1);

namespace Test\UseCases\PHPUnit;

use PHPUnit\Framework\TestCase;
use Test\UseCases\Support\UseCaseTestServiceContainer;

abstract class UseCaseTestCase extends TestCase
{
    protected UseCaseTestServiceContainer $container;

    /**
     * @before
     */
    protected function setUpContainer(): void
    {
        $this->container = UseCaseTestServiceContainer::create();
    }
}
