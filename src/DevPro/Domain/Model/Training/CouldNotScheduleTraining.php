<?php
declare(strict_types=1);

namespace DevPro\Domain\Model\Training;

use DateTimeImmutable;
use RuntimeException;

final class CouldNotScheduleTraining extends RuntimeException
{
    public static function becauseScheduledDateIsANationalHoliday(DateTimeImmutable $scheduledDate): self
    {
        return new self(
            'becauseScheduledDateIsANationalHoliday'
        );
    }

    public function userFacingMessage(): string
    {
        return 'The date of the training is a national holiday';
    }
}
