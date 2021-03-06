<?php
declare(strict_types=1);

namespace DevPro\Domain\Model\Training;

use DevPro\Domain\Model\Common\Country;
use DevPro\Domain\Model\Common\DateAndTime;
use RuntimeException;

final class CouldNotScheduleTraining extends RuntimeException
{
    public static function becauseTheDateIsANationalHolidayInThisCountry(DateAndTime $date, Country $country): self
    {
        return new self(
            sprintf(
                'The date of the training is a national holiday in %s', $country->asString()
            )
        );
    }
}
