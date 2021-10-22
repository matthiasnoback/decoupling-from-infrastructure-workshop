<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Holidays;

use MeetupOrganizing\Domain\Model\Common\Country;
use MeetupOrganizing\Domain\Model\Common\Date;
use MeetupOrganizing\Domain\Model\Common\NationalHoliday;

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
