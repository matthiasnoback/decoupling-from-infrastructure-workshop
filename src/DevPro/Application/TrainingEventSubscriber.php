<?php
/**
 * Class TrainingEventSubscriber
 * @package DevPro\Application *
 * @copyright   2020 UniWeb bvba
 * @since       2020-02-06 15:24
 * @author      michael.rosmane
 */

namespace DevPro\Application;

use DevPro\Domain\Model\Training\TrainingWasScheduled;

class TrainingEventSubscriber
{
    /**
     * @var UpcomingEventsRepository
     */
    private $upcomingEventsRepository;

    public function __construct(UpcomingEventsRepository $upcomingEventsRepository)
    {
        $this->upcomingEventsRepository = $upcomingEventsRepository;
    }

    public function whenTrainingWasScheduled(TrainingWasScheduled $event) {
        $item = new EventForList();

        $item->scheduledFor= $event->scheduledDate();
        $item->name= $event->title();
        $item->trainingId= $event->trainingId();

        $this->upcomingEventsRepository->add($item);
    }

}