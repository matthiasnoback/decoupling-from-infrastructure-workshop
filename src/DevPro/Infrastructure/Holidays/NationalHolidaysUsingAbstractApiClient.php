<?php
declare(strict_types=1);

namespace DevPro\Infrastructure\Holidays;

use DevPro\Application\NationalHolidays;
use DevPro\Domain\Model\Common\Country;
use DevPro\Domain\Model\Training\ScheduledDate;

final class NationalHolidaysUsingAbstractApiClient implements NationalHolidays
{
    private AbstractApiClient $client;

    public function __construct(AbstractApiClient $client)
    {
        $this->client = $client;
    }

    public function isNationalHolidayInCountry(ScheduledDate $date, Country $country): bool
    {
        return $this->client->getHolidays(
            $date->year(),
            $date->month(),
            $date->day(),
            $country->asString()
        ) !== [];
    }
}
