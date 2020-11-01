<?php
declare(strict_types=1);

namespace Test\Acceptance\Support;

use Assert\Assert;
use DateTimeImmutable;
use DevPro\Application\Clock;
use LogicException;

final class ClockForTesting implements Clock
{
    public const DATE_FORMAT = 'd-m-Y';

    private ?DateTimeImmutable $dateTime;

    public function setCurrentDate(string $date): void
    {
        $dateTime = DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $date);;
        Assert::that($dateTime)->isInstanceOf(DateTimeImmutable::class);

        $this->dateTime = $dateTime;
    }

    public function currentTime(): DateTimeImmutable
    {
        if (!$this->dateTime instanceof DateTimeImmutable) {
            throw new LogicException('First set the current date using setCurrentDate()');
        }

        return $this->dateTime;
    }
}
