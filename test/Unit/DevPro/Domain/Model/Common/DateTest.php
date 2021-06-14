<?php
declare(strict_types=1);

namespace DevPro\Domain\Model\Common;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class DateTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_not_be_created_from_an_invalid_date_string(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Date::fromString('2021-01-01 10:00');
    }

    /**
     * @test
     */
    public function it_can_be_created_from_a_string_and_turned_back_into_a_string(): void
    {
        $date = '2021-02-01';

        self::assertEquals($date, Date::fromString($date)->asString());
    }
}
