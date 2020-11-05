<?php
declare(strict_types=1);

namespace DevPro\Infrastructure\Holidays;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

final class AbstractApiClient
{
    private ClientInterface $client;
    private RequestFactoryInterface $requestFactory;

    public function __construct(ClientInterface $client, RequestFactoryInterface $requestFactory)
    {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
    }

    /**
     * @return array<string>
     */
    public function getHolidays(int $year, int $month, int $day, string $countryCode): array
    {
        $apiKey = '700a8df4a4424836b1988b854993a434';

        $response = $this->client->sendRequest(
            $this->requestFactory->createRequest(
                'GET',
                'https://holidays.abstractapi.com/v1/?' . http_build_query(
                    [
                        'api_key' => $apiKey,
                        'country' => $countryCode,
                        'year' => $year,
                        'month' => $month,
                        'day' => $day
                    ]
                )
            )
        );

        $responseBody = $response->getBody()->getContents();
        $decodedData = json_decode($responseBody, true);
        if (json_last_error()) {
            throw CouldNotGetHolidays::because(sprintf('AbstractApi returned invalid JSON: "%s"', $responseBody));
        }

        if (isset($decodedData['error'])) {
            throw CouldNotGetHolidays::because(
                sprintf('AbstractApi returned an error: %s', json_encode($decodedData['error']))
            );
        }

        if ($decodedData === null) {
            $decodedData = [];
        }

        return array_map(
            function (array $holidayData) use ($responseBody): string {
                if (!isset($holidayData['name'])) {
                    throw CouldNotGetHolidays::because(
                        'Expected key "name" was not defined, response body: ' . $responseBody);
                }

                return $holidayData['name'];
            },
            $decodedData
        );
    }
}
