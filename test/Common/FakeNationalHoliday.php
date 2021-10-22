<?php
declare(strict_types=1);

namespace Test\Common;

use MeetupOrganizing\Domain\Model\Common\Country;
use MeetupOrganizing\Domain\Model\Common\Date;
use MeetupOrganizing\Domain\Model\Common\NationalHoliday;

final class FakeNationalHoliday implements NationalHoliday
{
    /**
     * @var array<string,array<string>>
     */
    private array $holidays = [];

    public function isNationalHoliday(Country $country, Date $date): bool
    {
        return in_array($country->asString(), $this->holidays[$date->asString()] ?? [], true);
    }

    public function markAsNationalHoliday(string $date, string $country): void
    {
        $this->holidays[$date][] = $country;
    }
}
