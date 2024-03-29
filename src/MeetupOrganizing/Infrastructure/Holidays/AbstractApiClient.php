<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Holidays;

use GuzzleHttp\ClientInterface;

final class AbstractApiClient
{
    private ClientInterface $client;
    private string $baseUrl;
    private string $apiKey;

    public function __construct(ClientInterface $client, string $baseUrl, string $apiKey)
    {
        $this->client = $client;
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
    }

    /**
     * @return array<string>
     */
    public function getHolidays(int $year, int $month, int $day, string $countryCode): array
    {
        $response = $this->client->request(
            'GET',
            $this->baseUrl . '?' . http_build_query(
                [
                    'api_key' => $this->apiKey,
                    'country' => $countryCode,
                    'year' => $year,
                    'month' => $month,
                    'day' => $day
                ]
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

        if (!is_array($decodedData)) {
            throw CouldNotGetHolidays::because(sprintf('AbstractApi did not return an array: %s', $responseBody));
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
