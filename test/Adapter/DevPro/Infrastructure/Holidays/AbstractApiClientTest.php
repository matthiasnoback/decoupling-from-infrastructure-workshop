<?php
declare(strict_types=1);

namespace Adapter\DevPro\Infrastructure\Holidays;

use Asynchronicity\PHPUnit\Asynchronicity;
use DevPro\Infrastructure\ContainerConfiguration;
use DevPro\Infrastructure\Holidays\AbstractApiClient;
use DevPro\Infrastructure\Holidays\CouldNotGetHolidays;
use PHPUnit\Framework\TestCase;
use Test\Adapter\DevPro\Infrastructure\OutputAdapterTestServiceContainer;

final class AbstractApiClientTest extends TestCase
{
    use Asynchronicity;

    private AbstractApiClient $client;

    protected function setUp(): void
    {
        $container = new OutputAdapterTestServiceContainer(
            ContainerConfiguration::createForOutputAdapterTesting()
        );

        $this->client = $container->abstractApiClient();
    }

    /**
     * @test
     */
    public function it_can_fetch_holidays(): void
    {
        self::assertEventually(
            function () {
                self::assertEquals(
                    ['Christmas Day'],
                    $this->client->getHolidays(2020, 12, 25, 'NL')
                );
            }
        );
    }

    /**
     * @test
     */
    public function it_can_deal_with_errors(): void
    {
        self::assertEventually(
            function () {
                try {
                    $this->client->getHolidays(
                        2020,
                        12,
                        25,
                        'AA' // not a real country
                    );
                } catch (CouldNotGetHolidays $exception) {
                    self::assertStringContainsString('validation_error', $exception->getMessage());
                }
            }
        );
    }

    /**
     * @test
     */
    public function an_empty_response_will_be_taken_as_no_holidays(): void
    {
        self::assertEventually(
            function () {
                self::assertEquals(
                    [],
                    $this->client->getHolidays(2020, 12, 23, 'NL')
                );
            }
        );
    }
}
