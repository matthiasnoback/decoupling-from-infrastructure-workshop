<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain\Model\Common;

use Assert\Assert;
use DateTimeImmutable;
use InvalidArgumentException;
use Throwable;

final class DateAndTime
{
    public const DATE_TIME_FORMAT = 'Y-m-d H:i';

    private DateTimeImmutable $dateTime;

    private function __construct(DateTimeImmutable $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public static function fromString(string $dateTime): DateAndTime
    {
        try {
            $dateTimeImmutable = DateTimeImmutable::createFromFormat(self::DATE_TIME_FORMAT, $dateTime);
            Assert::that($dateTimeImmutable)->isInstanceOf(DateTimeImmutable::class);
        } catch (Throwable $throwable) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid date/time format. Provided: "%s", expected format: "%s"',
                    $dateTime,
                    self::DATE_TIME_FORMAT
                ),
                0,
                $throwable
            );
        }

        return new self($dateTimeImmutable);
    }

    public function asString(): string
    {
        return $this->dateTime->format(self::DATE_TIME_FORMAT);
    }

    public function asDate(): Date
    {
        return Date::fromDateTimeImmutable($this->dateTime);
    }

    public function isInTheFuture(DateTimeImmutable $now): bool
    {
        return $now < $this->dateTime;
    }

    public function toDateTimeImmutable(): DateTimeImmutable
    {
        return $this->dateTime;
    }

    public function year(): int
    {
        return (int)$this->dateTime->format('Y');
    }

    public function month(): int
    {
        return (int)$this->dateTime->format('m');
    }

    public function day(): int
    {
        return (int)$this->dateTime->format('d');
    }
}
