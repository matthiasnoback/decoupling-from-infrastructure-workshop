<?php
declare(strict_types=1);

namespace DevPro\Infrastructure\Database;

use Assert\Assert;
use DevPro\Domain\Model\Training\Training;
use DevPro\Domain\Model\Training\TrainingId;
use DevPro\Domain\Model\Training\TrainingRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Result;
use Ramsey\Uuid\Uuid;
use RuntimeException;

final class TrainingRepositoryUsingDbal implements TrainingRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
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
}
