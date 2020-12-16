<?php
declare(strict_types=1);

namespace Test\Adapter\DevPro\Infrastructure\Database;

use DevPro\Domain\Model\Common\Country;
use DevPro\Domain\Model\Training\ScheduledDate;
use DevPro\Domain\Model\Training\Training;
use DevPro\Domain\Model\Training\TrainingRepository;
use DevPro\Domain\Model\User\UserId;
use DevPro\Infrastructure\ContainerConfiguration;
use Test\Adapter\DevPro\Infrastructure\OutputAdapterTestServiceContainer;
use PHPUnit\Framework\TestCase;

final class TrainingRepositoryUsingDbalTest extends TestCase
{
    private TrainingRepository $repository;

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
            $this->anOrganizerId(),
            $this->aCountry(),
            $this->aTitle(),
            $this->aScheduledDate()
        );
        $training->releaseEvents();

        $this->repository->save($training);

        $fromDatabase = $this->repository->getById($training->trainingId());

        self::assertEquals($training, $fromDatabase);
    }

    private function anOrganizerId(): UserId
    {
        return UserId::fromString('e70775a1-f539-489a-80aa-3f113ac38e5a');
    }

    private function aCountry(): Country
    {
        return Country::fromString('NL');
    }

    private function aTitle(): string
    {
        return 'Title';
    }

    private function aScheduledDate(): ScheduledDate
    {
        return ScheduledDate::fromString('2020-01-24 09:30');
    }
}
