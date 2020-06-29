<?php

namespace DevPro\Domain\Model\Training;

use DateTimeImmutable;
use DevPro\Domain\Model\User\UserId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * @testdox Basic Training
 * @covers \DevPro\Domain\Model\Training\Training
 */
final class TrainingTest extends TestCase
{
    /**
     * @test
     * @testdox A training can be scheduled and retrieved
     */
    public function it_can_be_scheduled(): void
    {
        $trainingId = $this->someTrainingId();
        $organizerId = $this->someUserId();
        $title = $this->someTitle();
        $scheduledDate = $this->someDate();

        $training = Training::schedule($trainingId, $organizerId, $title, $scheduledDate);

        self::assertEquals(
            [new TrainingWasScheduled($trainingId, $title, $scheduledDate)],
            $training->releaseEvents()
        );
    }

    /**
     * @test
     * @testdox A training cannot be scheduled with an empty title
     */
    public function the_title_should_not_be_empty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/title/i');

        Training::schedule(
            $this->someTrainingId(),
            $this->someUserId(),
            $emptyTitle = '',
            $this->someDate()
        );
    }

    private function someTrainingId(): TrainingId
    {
        return TrainingId::fromString('eaa631d0-3760-43f5-a8cf-f239aadfe4aa');
    }

    private function someDate(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }

    private function someTitle(): string
    {
        return 'Some title';
    }

    private function someUserId()
    {
        return UserId::fromString('bb235de9-c15d-4bd8-9bc3-d31e4cc0e96f');
    }
}
