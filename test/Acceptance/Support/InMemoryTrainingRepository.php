<?php
declare(strict_types=1);

namespace Test\Acceptance\Support;

use DevPro\Domain\Model\Training\Training;
use DevPro\Domain\Model\Training\TrainingId;
use DevPro\Domain\Model\Training\TrainingRepository;
use Ramsey\Uuid\Uuid;
use RuntimeException;

final class InMemoryTrainingRepository implements TrainingRepository
{
    /**
     * @var array<Training> & Training[]
     */
    private array $entities = [];

    public function save(Training $entity): void
    {
        $this->entities[$entity->trainingId()->asString()] = $entity;
    }

    public function getById(TrainingId $id): Training
    {
        if (!isset($this->entities[$id->asString()])) {
            throw new RuntimeException('Could not find Training with ID ' . $id->asString());
        }

        return $this->entities[$id->asString()];
    }

    public function nextIdentity(): TrainingId
    {
        return TrainingId::fromString(Uuid::uuid4()->toString());
    }
}
