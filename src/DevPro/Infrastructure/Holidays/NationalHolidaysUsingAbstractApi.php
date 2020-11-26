<?php
declare(strict_types=1);

namespace DevPro\Infrastructure\Holidays;

use DateTimeImmutable;
use DevPro\Domain\Model\Common\Country;
use DevPro\Domain\Service\NationalHolidays;

final class NationalHolidaysUsingAbstractApi implements NationalHolidays
{
    private AbstractApiClient $client;

    public function __construct(AbstractApiClient $client)
    {
        $this->client = $client;
    }

    public function isANationalHolidayIn(DateTimeImmutable $date, Country $country): bool
    {
        $holidays = $this->client->getHolidays(
            (int)$date->format('Y'),
            (int)$date->format('m'),
            (int)$date->format('d'),
            $country->asString()
        );

        return count($holidays) > 0;
    }
}
