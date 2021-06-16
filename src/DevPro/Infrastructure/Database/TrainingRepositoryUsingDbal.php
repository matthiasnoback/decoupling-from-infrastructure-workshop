<?php
declare(strict_types=1);

namespace DevPro\Infrastructure\Database;

use Assert\Assert;
use DevPro\Application\Clock;
use DevPro\Application\UpcomingTraining;
use DevPro\Application\UpcomingTrainings;
use DevPro\Domain\Model\Training\Training;
use DevPro\Domain\Model\Training\TrainingId;
use DevPro\Domain\Model\Training\TrainingRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Result;
use Ramsey\Uuid\Uuid;
use RuntimeException;

final class TrainingRepositoryUsingDbal implements TrainingRepository, UpcomingTrainings
{
    private Connection $connection;
    private Clock $clock;

    public function __construct(Connection $connection, Clock $clock)
    {
        $this->connection = $connection;
        $this->clock = $clock;
    }

    public function save(Training $training): void
    {
        $this->connection->insert('trainings', $training->getDatabaseRecordData());
    }

    public function getById(TrainingId $trainingId): Training
    {
        $result = $this->connection->executeQuery(
            'SELECT * FROM trainings WHERE id = ?',
            [
                $trainingId->asString()
            ]
        );
        Assert::that($result)->isInstanceOf(Result::class);

        $record = $result->fetchAssociative();
        if ($record === false) {
            throw new RuntimeException(
                sprintf('Could not find training with ID "%s"', $trainingId->asString())
            );
        }

        return Training::fromDatabaseRecord($record);
    }

    public function nextIdentity(): TrainingId
    {
        return TrainingId::fromString(Uuid::uuid4()->toString());
    }

    public function findAllUpcomingTrainings(): array
    {
        $result = $this->connection->executeQuery(
            'SELECT * FROM trainings WHERE scheduledDate > ?',
            [
                $this->clock->currentTime()->format('Y-m-d')
            ]
        );
        Assert::that($result)->isInstanceOf(Result::class);

        $records = $result->fetchAllAssociative();

        return array_map(
            fn (array $record) => new UpcomingTraining(
                $record['title']
            ),
            $records
        );
    }
}
