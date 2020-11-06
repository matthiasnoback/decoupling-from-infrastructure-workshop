<?php
declare(strict_types=1);

namespace Test\Adapter\DevPro\Infrastructure;

use DevPro\Application\ApplicationInterface;
use DevPro\Application\Users\GetSecurityUser;
use DevPro\Infrastructure\AbstractDevelopmentServiceContainer;

final class InputAdapterTestServiceContainer extends AbstractDevelopmentServiceContainer
{
    public function application(): ApplicationInterface
    {
        return new ApplicationSpy();
    }

    public function getSecurityUser(): GetSecurityUser
    {
        return new HardCodedGetSecurityUser();
    }
}
