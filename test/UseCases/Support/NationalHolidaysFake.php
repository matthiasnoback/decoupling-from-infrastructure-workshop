<?php
declare(strict_types=1);

namespace Test\UseCases\Support;

use DevPro\Domain\Model\Common\Country;
use DevPro\Domain\Model\Training\ScheduledDate;
use DevPro\Domain\Service\NationalHolidays;

final class NationalHolidaysFake implements NationalHolidays
{
    /**
     * @var array<string,array<string,bool>>
     */
    private array $holidays;

    public function isNationalHoliday(Country $country, ScheduledDate $scheduledDate): bool
    {
        return $this->holidays[$country->asString()][$scheduledDate->asString()] ?? false;
    }

    public function markAsNationalHoliday(string $country, string $date): void
    {
        $this->holidays[$country][$date] = true;
    }
}
