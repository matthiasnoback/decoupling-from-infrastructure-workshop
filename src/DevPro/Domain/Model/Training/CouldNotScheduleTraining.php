<?php
declare(strict_types=1);

namespace DevPro\Domain\Model\Training;

use RuntimeException;

final class CouldNotScheduleTraining extends RuntimeException
{
    public static function becauseTheDateOfTheTrainingIsANationalHoliday(): self
    {
        return new self(
            'The date of the training is a national holiday'
        );
    }
}
