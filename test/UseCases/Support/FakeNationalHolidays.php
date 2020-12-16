<?php
declare(strict_types=1);

namespace Test\UseCases\Support;

use DevPro\Application\NationalHolidays;
use DevPro\Domain\Model\Common\Country;
use DevPro\Domain\Model\Training\ScheduledDate;

final class FakeNationalHolidays implements NationalHolidays
{
    private const DATE_FORMAT = 'Y-m-d';

    /**
     * @var array<string,array<string,bool>>
     */
    private array $nationalHolidays = [];

    public function isNationalHoliday(Country $country, ScheduledDate $date): bool
    {
        $date = $date->toDateTimeImmutable()->format(self::DATE_FORMAT);

        return $this->nationalHolidays[$country->asString()][$date] ?? false;
    }

    public function thisIsANationalHolidayIn(string $country, string $date): void
    {
        $this->nationalHolidays[$country][$date] = true;
    }

    public function thisIsNotANationalHolidayIn(string $country, string $date): void
    {
        $this->nationalHolidays[$country][$date] = false;
    }
}
