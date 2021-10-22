<?php
declare(strict_types=1);

namespace Test\Common;

use MeetupOrganizing\Domain\Model\Common\Country;
use MeetupOrganizing\Domain\Model\Common\Date;
use MeetupOrganizing\Domain\Model\Common\NationalHoliday;

final class FakeNationalHoliday implements NationalHoliday
{
    public function isNationalHoliday(Country $country, Date $date): bool
    {
        return $country->asString() === 'NL' && $date->asString() === '2020-12-25';
    }
}
