<?php
declare(strict_types=1);

namespace Test\Adapter\DevPro\Infrastructure;

use DevPro\Application\Users\SecurityUsers;
use DevPro\Domain\Model\User\UserRepository;
use DevPro\Infrastructure\AbstractDevelopmentServiceContainer;
use DevPro\Infrastructure\Holidays\AbstractApiClient;

final class OutputAdapterTestServiceContainer extends AbstractDevelopmentServiceContainer
{
    public function userRepository(): UserRepository
    {
        return parent::userRepository();
    }

    public function securityUsers(): SecurityUsers
    {
        return parent::securityUsers();
    }

    public function abstractApiClient(): AbstractApiClient
    {
        return parent::abstractApiClient();
    }
}
