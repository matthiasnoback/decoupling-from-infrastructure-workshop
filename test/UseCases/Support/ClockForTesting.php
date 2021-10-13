<?php
declare(strict_types=1);

namespace Test\UseCases\Support;

use DateTimeImmutable;
use MeetupOrganizing\Application\Clock;
use MeetupOrganizing\Domain\Model\Common\Date;
use LogicException;

final class ClockForTesting implements Clock
{
    private ?DateTimeImmutable $dateTime;

    public function setCurrentDate(Date $date): void
    {
        $this->dateTime = $date->asDateTimeImmutable();
    }

    public function currentTime(): DateTimeImmutable
    {
        if (!$this->dateTime instanceof DateTimeImmutable) {
            throw new LogicException('First set the current date using setCurrentDate()');
        }

        return $this->dateTime;
    }
}
