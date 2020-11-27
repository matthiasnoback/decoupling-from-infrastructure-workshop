<?php
declare(strict_types=1);

namespace Test\UseCases\Support;

use DevPro\Application\Holidays\NationalHolidays;
use DevPro\Domain\Model\Common\Country;
use DevPro\Domain\Model\Training\ScheduledDate;

final class NationalHolidaysFake implements NationalHolidays
{
    /**
     * @var array<string,array<int,array<int,array<int,bool>>>>
     */
    private array $nationalHolidays;

    public function isNationalHolidayInCountry(ScheduledDate $date, Country $country): bool
    {
        return $this->nationalHolidays[$country->asString()][$date->year()][$date->month()][$date->day()] ?? false;
    }

    public function markAsNationalHoliday(ScheduledDate $date, Country $country): void
    {
        $this->nationalHolidays[$country->asString()][$date->year()][$date->month()][$date->day()] = true;
    }
}
