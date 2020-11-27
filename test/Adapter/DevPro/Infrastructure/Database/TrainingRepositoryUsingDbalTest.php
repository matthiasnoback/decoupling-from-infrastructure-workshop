<?php
declare(strict_types=1);

namespace Test\Adapter\DevPro\Infrastructure\Database;

use DevPro\Domain\Model\Common\Country;
use DevPro\Domain\Model\Training\ScheduledDate;
use DevPro\Domain\Model\Training\Training;
use DevPro\Domain\Model\User\UserId;
use DevPro\Infrastructure\ContainerConfiguration;
use DevPro\Infrastructure\Database\TrainingRepositoryUsingDbal;
use Test\Adapter\DevPro\Infrastructure\OutputAdapterTestServiceContainer;
use PHPUnit\Framework\TestCase;

final class TrainingRepositoryUsingDbalTest extends TestCase
{
    private TrainingRepositoryUsingDbal $repository;

    protected function setUp(): void
    {
        $container = new OutputAdapterTestServiceContainer(
            ContainerConfiguration::createForOutputAdapterTesting(getenv())
        );

        $this->repository = $container->trainingRepository();
    }

    /**
     * @test
     */
    public function it_can_save_and_retrieve_a_training(): void
    {
        $training = Training::schedule(
            $this->repository->nextIdentity(),
            $this->someUserId(),
            $this->aCountry(),
            $this->someTitle(),
            $this->someDate()
        );
        $training->releaseEvents();

        $this->repository->save($training);

        $fromDatabase = $this->repository->getById($training->trainingId());

        self::assertEquals($training, $fromDatabase);
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
