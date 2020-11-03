<?php
declare(strict_types=1);

namespace Test\Adapter\DevPro\Infrastructure;

use DevPro\Application\ApplicationInterface;
use DevPro\Infrastructure\DevelopmentServiceContainer;

final class InputAdapterTestServiceContainer extends DevelopmentServiceContainer
{
    public function __construct()
    {
        parent::__construct(sys_get_temp_dir(), 'input_adapter_test');
    }

    public function application(): ApplicationInterface
    {
        return new ApplicationSpy();
    }
}
