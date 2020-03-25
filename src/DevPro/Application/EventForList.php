<?php
/**
 * Class TrainingForList
 * @package DevPro\Application *
 * @copyright   2020 UniWeb bvba
 * @since       2020-02-06 15:14
 * @author      michael.rosmane
 */

namespace DevPro\Application;

use DevPro\Domain\Model\Training\ScheduledDate;
use DevPro\Domain\Model\Training\TrainingId;

class EventForList
{
    /**
     * @var TrainingId
     */
    public $trainingId;

    /**
     * @var  ScheduledDate
     */
    public $scheduledFor;

    /**
     * @var string
     */
    public $name;

    /**
     * @var bool
     */
    public $isSoldOut;
}
