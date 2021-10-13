<?php
declare(strict_types=1);

namespace MeetupOrganizing\Domain\Model\Common;

use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class DateAndTimeTest extends TestCase
{
    /**
     * @test
     */
    public function it_normalizes_the_date_to_atom_format(): void
    {
        $dateAndTime = DateAndTime::fromString('2017-02-01 20:00');

        $this->assertEquals(
            new DateTimeImmutable('2017-02-01 20:00'),
            $dateAndTime->toDateTimeImmutable()
        );
    }

    /**
     * @test
     */
    public function it_can_be_converted_to_just_a_date(): void
    {
        $dateAndTime = DateAndTime::fromString('2017-02-01 20:00');

        $this->assertEquals(
            Date::fromString('2017-02-01')->asString(),
            $dateAndTime->asDate()->asString()
        );
    }

    /**
     * @test
     */
    public function it_fails_if_the_date_is_not_provided_in_the_right_format(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid date/time format. Provided: "2017-01-01 invalid", expected format: "Y-m-d H:i"');
        DateAndTime::fromString('2017-01-01 invalid');
    }

    /**
     * @test
     */
    public function it_extracts_year_month_and_day(): void
    {
        $dateAndTime = DateAndTime::fromString('2017-02-01 20:00');

        $this->assertSame(2017, $dateAndTime->year());
        $this->assertSame(2, $dateAndTime->month());
        $this->assertSame(1, $dateAndTime->day());
    }
}
