<?php
declare(strict_types=1);

namespace DevPro\Infrastructure;

use DevPro\Domain\Model\User\UserRepository;

final class AdapterTestServiceContainer extends DevelopmentServiceContainer
{
    public function __construct()
    {
        parent::__construct(__DIR__ . '/../../../../');
    }

    public function userRepository(): UserRepository
    {
        return parent::userRepository();
    }

    protected function environment(): string
    {
        return 'adapter_test';
    }
}
