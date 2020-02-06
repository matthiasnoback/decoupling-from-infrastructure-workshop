<?php
declare(strict_types=1);

namespace Test\Acceptance\Support;

use DevPro\Application\ListTrainings;
use DevPro\Application\EventForList;
use DevPro\Application\UpcomingEventsRepository;
use DevPro\Domain\Model\Training\Training;
use DevPro\Domain\Model\Training\TrainingId;
use DevPro\Domain\Model\Training\TrainingRepository;
use Ramsey\Uuid\Uuid;
use RuntimeException;

final class InMemoryUpcomingEventsRepository implements UpcomingEventsRepository
{

    /**
     * @var EventForList[]
     */
    private $records;

    public function list(\DateTimeImmutable $now): array
    {
        return array_filter($this->records, function(EventForList $event) use ($now) {
           return $event->scheduledFor->isInTheFuture($now);
        });
    }

    public function add(EventForList $training): void
    {
        $this->records[] = $training;
    }
}
