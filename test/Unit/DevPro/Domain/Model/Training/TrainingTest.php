<?php

namespace DevPro\Domain\Model\Training;

use DevPro\Domain\Model\Common\Country;
use DevPro\Domain\Model\User\UserId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class TrainingTest extends TestCase
{
    /**
     * @test
     */
    public function it_can_be_scheduled(): void
    {
        $trainingId = $this->someTrainingId();
        $organizerId = $this->someUserId();
        $country = $this->aCountry();
        $title = $this->someTitle();
        $scheduledDate = $this->someDate();

        $training = Training::schedule($trainingId, $organizerId, $country, $title, $scheduledDate);

        self::assertEquals(
            [new TrainingWasScheduled($trainingId, $organizerId, $country, $title, $scheduledDate)],
            $training->releaseEvents()
        );
    }

    /**
     * @test
     */
    public function the_title_should_not_be_empty(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/title/i');

        Training::schedule(
            $this->someTrainingId(),
            $this->someUserId(),
            $this->aCountry(),
            $emptyTitle = '',
            $this->someDate()
        );
    }

    private function someTrainingId(): TrainingId
    {
        return TrainingId::fromString('eaa631d0-3760-43f5-a8cf-f239aadfe4aa');
    }

    private function someDate(): ScheduledDate
    {
        return ScheduledDate::fromString('2020-11-26 14:26');
    }

    private function someTitle(): string
    {
        return 'Some title';
    }

    private function someUserId(): UserId
    {
        return UserId::fromString('bb235de9-c15d-4bd8-9bc3-d31e4cc0e96f');
    }

    private function aCountry(): Country
    {
        return Country::fromString('NL');
    }
}
