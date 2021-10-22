<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain\Model\Common;

use MeetupOrganizing\Infrastructure\Holidays\AbstractApiClient;

final class NationalHolidayApiClient implements NationalHoliday
{
    private AbstractApiClient $abstractApiClient;

    public function __construct(AbstractApiClient $abstractApiClient)
    {
        $this->abstractApiClient = $abstractApiClient;
    }

    public function isNationalHoliday(Country $country, Date $date): bool
    {
        return count($this->abstractApiClient->getHolidays($date->year(), $date->month(), $date->day(), $country->asString())) > 0;
    }
}
