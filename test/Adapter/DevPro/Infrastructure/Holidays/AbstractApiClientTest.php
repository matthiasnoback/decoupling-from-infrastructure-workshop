<?php
declare(strict_types=1);

namespace Adapter\DevPro\Infrastructure\Holidays;

use DevPro\Infrastructure\ContainerConfiguration;
use DevPro\Infrastructure\Holidays\AbstractApiClient;
use DevPro\Infrastructure\Holidays\CouldNotGetHolidays;
use PHPUnit\Framework\TestCase;
use Test\Adapter\DevPro\Infrastructure\OutputAdapterTestServiceContainer;

final class AbstractApiClientTest extends TestCase
{
    private AbstractApiClient $client;

    protected function setUp(): void
    {
        $container = new OutputAdapterTestServiceContainer(ContainerConfiguration::createForOutputAdapterTesting());

        $this->client = $container->abstractApiClient();
    }

    /**
     * @test
     */
    public function it_can_fetch_holidays(): void
    {
        self::assertEquals(
            ['Christmas Day'],
            $this->client->getHolidays(2020, 12, 25, 'NL')
        );
    }

    /**
     * @test
     */
    public function it_can_deal_with_errors(): void
    {
        $this->expectException(CouldNotGetHolidays::class);

        $this->client->getHolidays(
            2020,
            12,
            25,
            'AA' // not a real country
        );
    }

    /**
     * @test
     */
    public function an_empty_response_will_be_taken_as_no_holidays(): void
    {
        self::assertEquals(
            [],
            $this->client->getHolidays(2020, 12, 23, 'NL')
        );
    }
}
