<?php
declare(strict_types=1);

namespace DevPro\Domain\Model\Training;

use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ScheduledDateTest extends TestCase
{
    /**
     * @test
     */
    public function it_normalizes_the_date_to_atom_format(): void
    {
        $scheduledDate = ScheduledDate::fromString('2017-01-01 20:00');

        $this->assertEquals(
            new DateTimeImmutable('2017-01-01 20:00'),
            $scheduledDate->toDateTimeImmutable()
        );
    }

    /**
     * @test
     */
    public function it_fails_if_the_date_is_not_provided_in_the_right_format(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid date/time format. Provided: "2017-01-01 invalid", expected format: "Y-m-d H:i"');
        ScheduledDate::fromString('2017-01-01 invalid');
    }

    /**
     * @test
     */
    public function it_can_be_created_from_a_php_date_time_immutable(): void
    {
        $scheduledDate = ScheduledDate::fromDateTime(new DateTimeImmutable('2017-01-01 20:00'));

        $this->assertEquals(
            new DateTimeImmutable('2017-01-01 20:00'),
            $scheduledDate->toDateTimeImmutable()
        );
    }
}
