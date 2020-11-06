<?php
declare(strict_types=1);

namespace DevPro\Domain\Service;

use DateTimeImmutable;
use DevPro\Domain\Model\Common\Country;

interface NationalHolidays
{
    public function isANationalHolidayIn(DateTimeImmutable $date, Country $country): bool;
}
