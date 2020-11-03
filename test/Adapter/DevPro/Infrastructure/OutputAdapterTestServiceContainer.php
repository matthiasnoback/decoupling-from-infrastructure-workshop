<?php
declare(strict_types=1);

namespace Test\Adapter\DevPro\Infrastructure;

use DevPro\Domain\Model\User\UserRepository;
use DevPro\Infrastructure\DevelopmentServiceContainer;

final class OutputAdapterTestServiceContainer extends DevelopmentServiceContainer
{
    public function __construct()
    {
        parent::__construct(sys_get_temp_dir(), 'output_adapter_test');
    }

    public function userRepository(): UserRepository
    {
        return parent::userRepository();
    }
}
