<?php
declare(strict_types=1);

namespace DevPro\Application\Holidays;

use DevPro\Domain\Model\Common\Country;
use DevPro\Domain\Model\Training\ScheduledDate;

interface NationalHolidays
{
    public function isNationalHolidayInCountry(ScheduledDate $date, Country $country): bool;
}
