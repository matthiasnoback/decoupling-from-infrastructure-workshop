<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain\Model\Common;

use InvalidArgumentException;
use Symfony\Component\Intl\Countries;

final class Country
{
    private string $countryCode;

    private function __construct(string $countryCode)
    {
        if (!Countries::exists($countryCode)) {
            throw new InvalidArgumentException(sprintf('Invalid country code: "%s"', $countryCode));
        }

        $this->countryCode = $countryCode;
    }

    public static function fromString(string $countryCode): self
    {
        return new self($countryCode);
    }

    public function asString(): string
    {
        return $this->countryCode;
    }
}
