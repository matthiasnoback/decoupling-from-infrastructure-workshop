<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain\Model\Meetup;

use MeetupOrganizing\Domain\Model\Common\Country;
use MeetupOrganizing\Domain\Model\Common\DateAndTime;
use RuntimeException;

final class CouldNotScheduleMeetup extends RuntimeException
{
    public static function becauseTheDateIsANationalHolidayInThisCountry(DateAndTime $date, Country $country): self
    {
        return new self(
            sprintf(
                'The date of the meetup (%s) is a national holiday in this country (%s)', $date->asString(), $country->asString()
            )
        );
    }
}
