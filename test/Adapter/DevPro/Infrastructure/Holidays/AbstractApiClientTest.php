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
    private OutputAdapterTestServiceContainer $container;

    protected function setUp(): void
    {
        $this->container = new OutputAdapterTestServiceContainer(
            ContainerConfiguration::createForOutputAdapterTesting()
        );

        $this->client = $this->container->abstractApiClient();
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

// When using the MockHandler instead of the CurlHandler:

//    private function queueResponse(string $fixtureFilePath): void
//    {
//        $this->container->guzzleHttpHandler()->append(
//            new \GuzzleHttp\Psr7\Response(
//                200,
//                [
//                    'Content-Type' => 'application/json'
//                ],
//                file_get_contents($fixtureFilePath)
//            )
//        );
//    }
}
