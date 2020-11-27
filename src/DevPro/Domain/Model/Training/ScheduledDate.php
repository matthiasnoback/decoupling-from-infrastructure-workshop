<?php
declare(strict_types=1);

namespace DevPro\Domain\Model\Training;

use Assert\Assert;
use DateTimeImmutable;
use InvalidArgumentException;
use Throwable;

final class ScheduledDate
{
    public const DATE_TIME_FORMAT = 'Y-m-d H:i';

    private string $dateTime;

    private function __construct(string $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public static function fromString(string $dateTime): ScheduledDate
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

        return self::fromDateTime($dateTimeImmutable);
    }

    public static function fromDateTime(DateTimeImmutable $dateTime): ScheduledDate
    {
        return new self($dateTime->format(self::DATE_TIME_FORMAT));
    }

    public function asString(): string
    {
        return $this->dateTime;
    }

    public function isInTheFuture(DateTimeImmutable $now): bool
    {
        return $now < $this->toDateTimeImmutable();
    }

    public function toDateTimeImmutable(): DateTimeImmutable
    {
        $dateTimeImmutable = DateTimeImmutable::createFromFormat(self::DATE_TIME_FORMAT, $this->dateTime);
        Assert::that($dateTimeImmutable)->isInstanceOf(DateTimeImmutable::class);

        return $dateTimeImmutable;
    }

    public function year(): int
    {
        return (int)$this->toDateTimeImmutable()->format('Y');
    }

    public function month(): int
    {
        return (int)$this->toDateTimeImmutable()->format('n');
    }

    public function day(): int
    {
        return (int)$this->toDateTimeImmutable()->format('j');
    }
}
