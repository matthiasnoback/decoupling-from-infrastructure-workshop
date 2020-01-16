<?php
declare(strict_types=1);

namespace DevPro\Domain\Model\Training;

use Common\DomainModel\AggregateId;

final class TrainingId
{
    use AggregateId;

    public function asString(): string
    {
        return $this->__toString();
    }
}
