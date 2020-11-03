<?php
declare(strict_types=1);

namespace DevPro\Infrastructure;

use DevPro\Domain\Model\User\UserRepository;

final class AdapterTestServiceContainer extends DevelopmentServiceContainer
{
    public function __construct()
    {
        parent::__construct(sys_get_temp_dir(), 'adapter_test');
    }

    public function userRepository(): UserRepository
    {
        return parent::userRepository();
    }
}
