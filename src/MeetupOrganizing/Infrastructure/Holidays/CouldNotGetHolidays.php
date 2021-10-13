<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Holidays;

use Exception;
use RuntimeException;

final class CouldNotGetHolidays extends RuntimeException
{
    public static function because(string $message, Exception $previous = null): self
    {
        return new self(
            $message,
            0,
            $previous
        );
    }
}
