<?php
declare(strict_types=1);

namespace Test\UseCases\Support;

use DateTimeImmutable;
use DevPro\Domain\Model\Common\Country;
use DevPro\Domain\Service\NationalHolidays;

final class FakeNationalHolidays implements NationalHolidays
{
    private array $nationalHolidays;

    public function isANationalHolidayIn(DateTimeImmutable $date, Country $country): bool
    {
        return $this->nationalHolidays[$country->asString()][$date->format('d-m-Y')] ?? false;
    }

    public function thisIsANationalHolidayIn(string $date, string $country): void
    {
        $this->nationalHolidays[$country][$date] = true;
    }

    public function thisIsNotANationalHolidayIn(string $date, string $country): void
    {
        $this->nationalHolidays[$country][$date] = false;
    }
}
