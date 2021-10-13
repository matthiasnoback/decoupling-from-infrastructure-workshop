<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain\Model\Common;

use DateTimeImmutable;
use InvalidArgumentException;

final class Date
{
    private const DATE_FORMAT = 'Y-m-d';
    private DateTimeImmutable $dateTimeImmutable;

    private function __construct(DateTimeImmutable $dateTimeImmutable)
    {
        $this->dateTimeImmutable = $dateTimeImmutable;
    }

    public static function fromString(string $date): self
    {
        $dt = DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $date);
        if (!$dt instanceof DateTimeImmutable) {
            throw new InvalidArgumentException(
                sprintf(
                    'Invalid date string provided: %s. Expected format: %s',
                    $date,
                    self::DATE_FORMAT
                )
            );
        }

        return new self($dt);
    }

    public static function fromDateTimeImmutable(DateTimeImmutable $dateTime): self
    {
        return new self($dateTime);
    }

    public function asString(): string
    {
        return $this->dateTimeImmutable->format(self::DATE_FORMAT);
    }

    public function asDateTimeImmutable(): DateTimeImmutable
    {
        return $this->dateTimeImmutable;
    }

    public function year(): int
    {
        return (int)$this->dateTimeImmutable->format('Y');
    }

    public function month(): int
    {
        return (int)$this->dateTimeImmutable->format('m');
    }

    public function day(): int
    {
        return (int)$this->dateTimeImmutable->format('d');
    }
}
