<?php

namespace MeetupOrganizing\Domain\Model\Common;

use Assert\Assertion;

trait Mapping
{
    private static function getString(array $record, string $key): string
    {
        Assertion::keyExists($record, $key);
        Assertion::string($record[$key]);

        return $record[$key];
    }
}
