<?php
declare(strict_types=1);

namespace DevPro\Domain\Model\Training;

use RuntimeException;

interface TrainingRepository
{
    public function save(Training $training): void;

    /**
     * @throws RuntimeException When the entity could not be found
     */
    public function getById(TrainingId $trainingId): Training;
}
