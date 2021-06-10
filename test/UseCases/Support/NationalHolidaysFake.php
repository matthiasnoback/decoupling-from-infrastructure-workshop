<?php
declare(strict_types=1);

namespace Test\UseCases\Support;

use DevPro\Application\NationalHolidays;
use DevPro\Domain\Model\Common\Country;
use DevPro\Domain\Model\Training\ScheduledDate;

final class NationalHolidaysFake implements NationalHolidays
{
    /**
     * @var array<string,array<string,bool>>
     */
    private array $nationalHolidays = [];

    public function isNationalHolidayInCountry(ScheduledDate $date, Country $country): bool
    {
        return $this->nationalHolidays[$country->asString()][$date->asDateString()] ?? false;
    }

    public function markAsHoliday(string $date, string $country): void
    {
        $this->nationalHolidays[$country][$date] = true;
    }
}
