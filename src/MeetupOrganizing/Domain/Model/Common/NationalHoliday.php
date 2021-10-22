<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain\Model\Common;

interface NationalHoliday
{
    public function isNationalHoliday(Country $country, Date $date): bool;
}
