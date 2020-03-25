<?php
/**
 * Class UpcomingEvents
 * @package DevPro\Application *
 * @copyright   2020 UniWeb bvba
 * @since       2020-02-06 15:38
 * @author      michael.rosmane
 */

namespace DevPro\Application;

use DevPro\Domain\Model\Training\TrainingId;
use RuntimeException;

interface UpcomingEventsRepository
{
    public function list(\DateTimeImmutable $now): array;

    public function add(EventForList $training): void;

    /**
     * @throws RuntimeException
     */
    public function getById(TrainingId $trainingId): EventForList;

    public function update(EventForList $eventForList): void;
}
