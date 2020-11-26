<?php
declare(strict_types=1);

namespace Test\Adapter\DevPro\Infrastructure;

use DevPro\Application\ApplicationInterface;
use DevPro\Application\Users\SecurityUsers;
use DevPro\Infrastructure\AbstractDevelopmentServiceContainer;

final class InputAdapterTestServiceContainer extends AbstractDevelopmentServiceContainer
{
    public function application(): ApplicationInterface
    {
        return new ApplicationSpy();
    }

    protected function securityUsers(): SecurityUsers
    {
        return new HardCodedSecurityUsers();
    }
}
