<?php
declare(strict_types=1);

namespace Test\Adapter\MeetupOrganizing\Infrastructure;

use MeetupOrganizing\Application\ApplicationInterface;
use MeetupOrganizing\Application\Users\Users;
use MeetupOrganizing\Infrastructure\AbstractDevelopmentServiceContainer;

final class InputAdapterTestServiceContainer extends AbstractDevelopmentServiceContainer
{
    public function boot(): void
    {
        parent::boot();

        $this->schemaManager()->truncateTables();
    }

    public function application(): ApplicationInterface
    {
        return new ApplicationSpy();
    }

    protected function securityUsers(): Users
    {
        return new HardCodedUsers();
    }
}
