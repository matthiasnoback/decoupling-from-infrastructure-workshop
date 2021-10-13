<?php
declare(strict_types=1);

namespace Test\Adapter\MeetupOrganizing\Infrastructure;

use MeetupOrganizing\Application\Users\SecurityUsers;
use MeetupOrganizing\Domain\Model\User\UserRepository;
use MeetupOrganizing\Infrastructure\AbstractDevelopmentServiceContainer;
use MeetupOrganizing\Infrastructure\Holidays\AbstractApiClient;

final class OutputAdapterTestServiceContainer extends AbstractDevelopmentServiceContainer
{
// When using Guzzle's MockHandler:

//    private ?\GuzzleHttp\Handler\MockHandler $guzzleMockHandler = null;

    public function boot(): void
    {
        parent::boot();

        $this->schemaManager()->truncateTables();
    }

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

// Alternative to using the CurlHandler:
//
//    /**
//     * @return \GuzzleHttp\Handler\MockHandler
//     */
//    public function guzzleHttpHandler(): callable
//    {
//        return $this->guzzleMockHandler ?? $this->guzzleMockHandler = new \GuzzleHttp\Handler\MockHandler();
//    }

// Alternative to calling abstractapi.com:

//    protected function abstractApiBaseUrl(): string
//    {
//        return 'http://fake_abstractapi:8080';
//    }
}
