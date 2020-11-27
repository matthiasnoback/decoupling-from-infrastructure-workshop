<?php
declare(strict_types=1);

namespace DevPro\Infrastructure\Holidays;

use DevPro\Application\Holidays\NationalHolidays;
use DevPro\Domain\Model\Common\Country;
use DevPro\Domain\Model\Training\ScheduledDate;

final class NationalHolidaysUsingAbstractApi implements NationalHolidays
{
    private AbstractApiClient $apiClient;

    public function __construct(AbstractApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function isNationalHolidayInCountry(ScheduledDate $date, Country $country): bool
    {
        $holidays = $this->apiClient->getHolidays(
            $date->year(),
            $date->month(),
            $date->day(),
            $country->asString()
        );

        return count($holidays) > 0;
    }
}
